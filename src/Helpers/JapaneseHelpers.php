<?php

namespace Limelight\Helpers;

trait JapaneseHelpers
{
    /**
     * Return true if word contains kanji.
     *
     * @param string $word
     *
     * @return bool
     */
    protected function hasKanji($word)
    {
        $kanjiPattern = '\p{Han}';

        if (mb_ereg($kanjiPattern, $word) === false) {
            return false;
        }

        return true;
    }

    /**
     * Return true if character is katakana.
     *
     * @param string $character
     *
     * @return bool
     */
    protected function isKatakana($character)
    {
        $kanaPattern = '[ァ-・ヽヾ゛゜ー]';

        return mb_ereg($kanaPattern, $character);
    }

    /**
     * Get character array from string.
     *
     * @param string $string
     *
     * @return array
     */
    protected function getChars($string)
    {
        return preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
    }
}
