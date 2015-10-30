<?php

namespace Limelight\Plugins\Library\Romanji;

abstract class RomanjiStyle
{
    /**
     * Convert string to romanji.
     *
     * @param string $string
     * @param LimelightWord $word
     *
     * @return string
     */
    abstract public function convert($string, $word);

    /**
     * Value is in conversions array.
     *
     * @param string $value
     *
     * @return bool
     */
    protected function canBeRomanji($value)
    {
        return in_array($value, array_keys($this->conversions));
    }

    /**
     * Value is in edible array.
     *
     * @param string $value
     *
     * @return bool
     */
    protected function isEdible($value)
    {
        return in_array($value, $this->edible);
    }

    /**
     * Find combos through recursion.
     *
     * @param string $current
     * @param string $next
     * @param string $nextNext
     *
     * @return string
     */
    protected function findCombos($current, $next = null, $nextNext = null)
    {
        if ($this->isEdible($next)) {
            $combo = $current.$next;

            if ($this->canBeRomanji($combo)) {
                $current = $combo;

                $current = $this->findCombos($combo, $nextNext);

                $this->eat += 1;
            }
        }

        return $current;
    }

    /**
     * Capitalize proper nouns.
     * 
     * @param  string $romanji
     * @param  LimelightWord $word
     * 
     * @return string
     */
    protected function upperCaseNames($romanji, $word)
    {
        if ($word->partOfSpeech === 'proper noun') {
            return ucfirst($romanji);
        }

        return $romanji;
    }
}
