<?php

namespace Limelight\Parse;

use Limelight\Classes\LimelightWord;
use Limelight\Events\Dispatcher;
use Limelight\Limelight;
use Limelight\Parse\PartOfSpeech\POSRegistry;

class TokenParser
{
    /**
     * @var Limelight
     */
    private $limelight;

    /**
     * @var Dispatcher
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
        'partOfSpeech' => null,
        'grammar' => null,
        'eatNext' => false,
        'eatLemma' => true,
        'attachToPrevious' => false,
        'alsoAttachToLemma' => false,
        'updatePOS' => false,
    ];

    /**
     * Construct.
     *
     * @param Limelight $limelight
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

            $previousToken = ($i > 0 ? $tokens[$i - 1] : null);

            $currentToken = $tokens[$i];

            $nextToken = ($i + 1 < $length ? $tokens[$i + 1] : null);

            $properties = $this->getProperties($registry, $previousWord, $previousToken, $currentToken, $nextToken);

            if ($properties['attachToPrevious'] && count($this->words) > 0) {
                $this->appendWordToLast($currentToken, $properties, $previousWord);
            } else {
                $this->makeNewWord($currentToken, $properties);
            }

            if ($properties['eatNext']) {
                $this->eatNextToken($nextToken, $properties);

                $i += 1;
            }
        }

        return $this->words;
    }

    /**
     * Get properties for current token.
     *
     * @param POSRegistry $registry
     * @param LimelightWord|bool $previousWord
     * @param array $previousToken
     * @param array $currentToken
     * @param array $nextToken
     *
     * @return array
     */
    private function getProperties(
        POSRegistry $registry,
        $previousWord,
        $previousToken,
        $currentToken,
        $nextToken
    ) {
        $className = ucfirst($currentToken['partOfSpeech1']);

        $POSClass = $registry->getClass($className);

        return $POSClass->handle(
            $this->defaults,
            $previousWord,
            $previousToken,
            $currentToken,
            $nextToken
        );
    }

    /**
     * Update current if reading does not exist.
     *
     * @param array $currentToken
     * @return array
     */
    private function updateCurrent($currentToken)
    {
        $currentToken['lemma'] = $currentToken['literal'];

        $katakana = mb_convert_kana($currentToken['literal'], 'C');

        $currentToken['reading'] = $katakana;

        $currentToken['pronunciation'] = $katakana;

        return $currentToken;
    }

    /**
     * Append current word to last word in words array.
     *
     * @param array   $currentToken
     * @param array $properties
     * @param LimelightWord|bool $previousWord
     */
    private function appendWordToLast($currentToken, $properties, $previousWord)
    {
        $previousWord->appendTo('rawMecab', $currentToken);

        $previousWord->appendTo('word', $currentToken['literal']);

        $previousWord->appendTo('reading', $currentToken['reading']);

        $previousWord->appendTo('pronunciation', $currentToken['pronunciation']);

        if ($properties['alsoAttachToLemma']) {
            $previousWord->appendTo('lemma', $currentToken['lemma']);
        }

        if ($properties['updatePOS']) {
            $previousWord->setPartOfSpeech($properties['partOfSpeech']);
        }
    }

    /**
     * Make new word and append it to words array.
     *
     * @param array $currentToken
     * @param array $properties
     */
    private function makeNewWord($currentToken, $properties)
    {
        $word = new LimelightWord($currentToken, $properties, $this->limelight);

        $this->dispatcher->fire('WordWasCreated', $word);

        $this->words[] = $word;
    }

    /**
     * Eat the next token.
     *
     * @param array $nextToken
     * @param array $properties
     */
    private function eatNextToken($nextToken, $properties)
    {
        $word = end($this->words);

        $word->appendTo('rawMecab', $nextToken);

        $word->appendTo('word', $nextToken['literal']);

        $word->appendTo('reading', $nextToken['reading']);

        $word->appendTo('pronunciation', $nextToken['pronunciation']);

        if ($properties['eatLemma']) {
            $word->appendTo('lemma', $nextToken['lemma']);
        }
    }
}
