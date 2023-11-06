<?php

declare(strict_types=1);

namespace Limelight\Plugins\Library\Romaji;

use Limelight\Classes\LimelightWord;

abstract class RomajiConverter
{
    /**
     * Number of index values to eat.
     */
    protected int $eat;

    /**
     * Can be combined with other characters.
     */
    protected array $edible = [
        'ゃ',
        'ゅ',
        'ょ',
        'ぇ',
        'ぃ',
        'あ',
        'い',
        'う',
        'え',
        'お',
    ];

    /**
     * Handle conversion request.
     */
    abstract protected function handle(string $string, LimelightWord $word): string;

    /**
     * Convert string to romaji.
     */
    protected function convert(string $string, LimelightWord $word): string
    {
        $this->eat = 0;

        $characters = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);

        $count = count($characters);

        $results = '';

        for ($index = 0; $index < $count; $index++) {
            $index += $this->eat;

            if ($index >= $count) {
                break;
            }

            $this->eat = 0;

            $char = $characters[$index];

            $next = $characters[$index + 1] ?? null;

            $nextNext = $characters[$index + 2] ?? null;

            $charToConvert = $this->findCombos($char, $next, $nextNext);

            if ($char === 'っ' && $next && $this->canBeRomaji($next)) {
                $results .= $this->convertSmallTsu($next);

                continue;
            }

            if (isset($this->conversions[$charToConvert])) {
                $convertedChar = $this->conversions[$charToConvert];
            } elseif (preg_match('/[0-9.!?,;:-]$/', $charToConvert)) {
                $convertedChar = $charToConvert;
            } else {
                $convertedChar = '';
            }

            if ($convertedChar === 'n') {
                $convertedChar = $this->convertN($next, $convertedChar);
            }

            if ($this->particleCanBeConverted($word, $convertedChar)) {
                $convertedChar = $this->particleConversions[$convertedChar];
            }

            if ($this->verbCanBeCombined($convertedChar, $results, $word, $index)) {
                $convertedChar = $this->getConvertedChar($convertedChar, $results);

                $results = substr($results, 0, -1);
            }

            if ($convertedChar === '' && ctype_alpha($char)) {
                $results .= $char;
            } else {
                $results .= $convertedChar;
            }
        }

        return $this->upperCaseNames($results, $word);
    }

    /**
     * Find combos through recursion.
     */
    private function findCombos(string $current, ?string $next, ?string $nextNext = null): string
    {
        if ($next && $this->isEdible($next)) {
            $combo = $current.$next;

            if ($this->canBeRomaji($combo)) {
                $current = $this->findCombos($combo, $nextNext);

                $this->eat++;
            }
        }

        return $current;
    }

    /**
     * Value is in edible array.
     */
    private function isEdible(string $value): bool
    {
        return in_array($value, $this->edible, true);
    }

    /**
     * Value is in conversions array.
     */
    private function canBeRomaji(string $value): bool
    {
        return array_key_exists($value, $this->conversions);
    }

    /**
     * Get the next char from the next hiragana.
     */
    private function convertSmallTsu(string $next): string
    {
        $nextRomaji = $this->conversions[$next];

        $nextChar = preg_split('//u', $nextRomaji, -1, PREG_SPLIT_NO_EMPTY)[0];

        return $this->tsuConversions[$nextChar] ?? $nextChar;
    }

    /**
     * Convert n if possible.
     */
    private function convertN(?string $next, string $convertedChar): string
    {
        $nextRomaji = $this->conversions[$next] ?? null;

        if (is_null($nextRomaji)) {
            return $convertedChar;
        }

        $nextChar = substr($nextRomaji, 0, 1);

        return $this->nConversions[$nextChar] ?? $convertedChar;
    }

    /**
     * Char is particle and in particle conversions array.
     */
    private function particleCanBeConverted(LimelightWord $word, string $convertedChar): bool
    {
        return $word->partOfSpeech === 'postposition' &&
            array_key_exists($convertedChar, $this->particleConversions);
    }

    /**
     * Verb can be combined with the previous verb.
     */
    private function verbCanBeCombined(string $convertedChar, string $results, LimelightWord $word, int $index): bool
    {
        return $this->equalsPrevious($convertedChar, $results) &&
            $this->inComboArray($convertedChar) &&
            $this->hasLongSound($word, $index);
    }

    /**
     * Char equals the last char on the results string.
     */
    private function equalsPrevious(string $convertedChar, string $results): bool
    {
        return $convertedChar === substr($results, -1) ||
            ($convertedChar === 'u' && substr($results, -1) === 'o');
    }

    /**
     * The converted char is in the verbCombos array.
     */
    private function inComboArray(string $convertedChar): bool
    {
        return array_key_exists($convertedChar, $this->verbCombos);
    }

    /**
     * The word pronunciation string indicates a long vowel sound.
     */
    private function hasLongSound(LimelightWord $word, int $index): bool
    {
        return mb_substr($word->pronunciation, $index, 1) === 'ー';
    }

    /**
     * Get new char from verbCombos array.
     */
    private function getConvertedChar(string $convertedChar, string $results): string
    {
        if ($convertedChar === 'u' && substr($results, -1) === 'o') {
            return $this->verbCombos['o'];
        }

        return $this->verbCombos[$convertedChar];
    }

    /**
     * Capitalize proper nouns.
     */
    private function upperCaseNames(string $romaji, LimelightWord $word): string
    {
        if ($word->partOfSpeech === 'proper noun') {
            return mb_convert_case($romaji, MB_CASE_TITLE, 'UTF-8');
        }

        return $romaji;
    }
}
