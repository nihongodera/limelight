<?php

namespace Limelight\Parse;

use Limelight\Limelight;
use Limelight\Events\Dispatcher;
use Limelight\Classes\LimelightWord;
use Limelight\Parse\PartOfSpeech\POSRegistry;

class TokenParser
{
    /**
     * @var Limelight\Limelight
     */
    private $limelight;

    /**
     * @var Limelight\Events\Dispatcher
     */
    private $dispatcher;

    /**
     * @var array
     */
    private $words = [];

    /**
     * Default properties for parsing tokens.
     *
     * @var array
     */
    private $defaults = [
        'partOfSpeech'      => null,
        'grammar'           => null,
        'eatNext'           => false,
        'eatLemma'          => true,
        'attachToPrevious'  => false,
        'alsoAttachToLemma' => false,
        'updatePOS'         => false,
    ];

    /**
     * Construct.
     *
     * @param Limelight  $limelight
     * @param Dispatcher $dispatcher
     */
    public function __construct(Limelight $limelight, Dispatcher $dispatcher)
    {
        $this->limelight = $limelight;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Parse the text by filtering through the tokens.
     *
     * @param array $tokens
     *
     * @return array
     */
    public function parseTokens($tokens)
    {
        $registry = POSRegistry::getInstance();

        $length = count($tokens);

        for ($i = 0; $i < $length; ++$i) {
            if (!isset($tokens[$i]['partOfSpeech1'])) {
                continue;
            }

            $previousWord = end($this->words);

            $previous = ($i > 0 ? $tokens[$i - 1] : null);

            $current = $tokens[$i];

            $next = ($i + 1 < $length ? $tokens[$i + 1] : null);

            $properties = $this->getProperties($registry, $previousWord, $previous, $current, $next);

            if ($properties['attachToPrevious'] && count($this->words) > 0) {
                $this->appendWordToLast($current, $properties, $previousWord);
            } else {
                $this->makeNewWord($current, $properties);
            }

            if ($properties['eatNext']) {
                $this->eatNextToken($current, $next, $properties);

                $i += 1;
            }
        }

        return $this->words;
    }

    /**
     * Get properties for current token.
     *
     * @param POSRegistry        $registry
     * @param LimelightWord|bool $previousWord
     * @param array              $previous
     * @param array              $current
     * @param array              $next
     *
     * @return array
     */
    private function getProperties(
        POSRegistry $registry,
        $previousWord,
        $previous,
        $current,
        $next
    ) {
        $className = ucfirst($current['partOfSpeech1']);

        $POSClass = $registry->getClass($className);

        $properties = $POSClass->handle(
            $this->defaults,
            $previousWord,
            $previous,
            $current,
            $next
        );

        return $properties;
    }

    /**
     * Update current if reading does not exist.
     *
     * @param array $current
     *
     * @return array
     */
    private function updateCurrent($current)
    {
        $current['lemma'] = $current['literal'];

        $katakana = mb_convert_kana($current['literal'], 'C');

        $current['reading'] = $katakana;

        $current['pronunciation'] = $katakana;

        return $current;
    }

    /**
     * Append current word to last word in words array.
     *
     * @param array              $current
     * @param array              $properties
     * @param LimelightWord|bool $previousWord
     */
    private function appendWordToLast($current, $properties, $previousWord)
    {
        $previousWord->appendTo('rawMecab', $current);

        $previousWord->appendTo('word', $current['literal']);

        $previousWord->appendTo('reading', $current['reading']);

        $previousWord->appendTo('pronunciation', $current['pronunciation']);

        if ($properties['alsoAttachToLemma']) {
            $previousWord->appendTo('lemma', $current['lemma']);
        }

        if ($properties['updatePOS']) {
            $previousWord->setPartOfSpeech($properties['partOfSpeech']);
        }
    }

    /**
     * Make new word and append it to words array.
     *
     * @param array $current
     * @param array $properties
     */
    private function makeNewWord($current, $properties)
    {
        $word = new LimelightWord($current, $properties, $this->limelight);

        $this->dispatcher->fire('WordWasCreated', $word);

        $this->words[] = $word;
    }

    /**
     * Eat the next token.
     *
     * @param array $current
     * @param array $next
     * @param array $properties
     */
    private function eatNextToken($current, $next, $properties)
    {
        $word = end($this->words);

        $word->appendTo('rawMecab', $next);

        $word->appendTo('word', $next['literal']);

        $word->appendTo('reading', $next['reading']);

        $word->appendTo('pronunciation', $next['pronunciation']);

        if ($properties['eatLemma']) {
            $word->appendTo('lemma', $next['lemma']);
        }
    }
}
