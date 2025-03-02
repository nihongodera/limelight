<?php

declare(strict_types=1);

namespace Limelight\Parse;

use Limelight\Limelight;
use Limelight\Events\Dispatcher;
use Limelight\Classes\LimelightWord;
use Limelight\Parse\PartOfSpeech\POSRegistry;

class TokenParser
{
    private Limelight $limelight;

    private Dispatcher $dispatcher;

    private array $words = [];

    /**
     * Default properties for parsing tokens.
     */
    private array $defaults = [
        'partOfSpeech'      => null,
        'grammar'           => null,
        'eatNext'           => false,
        'eatLemma'          => true,
        'attachToPrevious'  => false,
        'alsoAttachToLemma' => false,
        'updatePOS'         => false,
    ];

    public function __construct(Limelight $limelight, Dispatcher $dispatcher)
    {
        $this->limelight = $limelight;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Parse the text by filtering through the tokens.
     */
    public function parseTokens(array $tokens): array
    {
        $registry = POSRegistry::getInstance();

        $length = count($tokens);

        for ($i = 0; $i < $length; $i++) {
            if (!isset($tokens[$i]['partOfSpeech1'])) {
                continue;
            }

            $previousWord = ($end = end($this->words)) ? $end : null;

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

                $i++;
            }
        }

        return $this->words;
    }

    /**
     * Get properties for current token.
     */
    private function getProperties(
        POSRegistry $registry,
        ?LimelightWord $previousWord,
        ?array $previousToken,
        array $currentToken,
        ?array $nextToken
    ): array {
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
     */
    private function updateCurrent(array $currentToken): array
    {
        $currentToken['lemma'] = $currentToken['literal'];

        $katakana = mb_convert_kana($currentToken['literal'], 'C');

        $currentToken['reading'] = $katakana;

        $currentToken['pronunciation'] = $katakana;

        return $currentToken;
    }

    /**
     * Append current word to last word in words array.
     */
    private function appendWordToLast(array $currentToken, array $properties, LimelightWord $previousWord): void
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
     */
    private function makeNewWord(array $currentToken, array $properties): void
    {
        $word = new LimelightWord($currentToken, $properties, $this->limelight);

        $this->dispatcher->fire('WordWasCreated', $word);

        $this->words[] = $word;
    }

    /**
     * Eat the next token.
     */
    private function eatNextToken(array $nextToken, array $properties): void
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
