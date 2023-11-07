<?php

declare(strict_types=1);

namespace Limelight\Helpers;

trait JapaneseHelpers
{
    /**
     * Return true if word contains kanji.
     */
    protected function hasKanji(string $word): bool
    {
        $kanjiPattern = '\p{Han}';

        return (bool) mb_ereg($kanjiPattern, $word);
    }

    /**
     * Return true if character is katakana.
     */
    protected function isKatakana(string $character): bool
    {
        $kanaPattern = '[ァ-・ヽヾ゛゜ー]';

        return (bool) mb_ereg($kanaPattern, $character);
    }

    /**
     * Get character array from string.
     */
    protected function getChars(string $string): array
    {
        return preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
    }
}
