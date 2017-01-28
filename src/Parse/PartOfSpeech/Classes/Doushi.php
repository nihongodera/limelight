<?php

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class Doushi implements PartOfSpeech
{
    /**
     * Handle the parsing request.
     *
     * @param array $properties
     * @param array $previousWord [previous word]
     * @param array $previous     [previous token]
     * @param array $current      [current token]
     * @param array $next         [next token]
     *
     * @return array
     */
    public function handle(array $properties, $previousWord, $previous, array $current, $next)
    {
        $properties['partOfSpeech'] = 'verb';

        if ($current['partOfSpeech2'] === 'setsubi') {
            $properties['attachToPrevious'] = true;
        } elseif ($this->isHijiritsuNotMeireiI($current)) {
            $properties['attachToPrevious'] = true;
        }

        return $properties;
    }

    /**
     * Return true if POS is hijiritsu and inflection is not meireiI.
     *
     * @param array $current
     *
     * @return bool
     */
    protected function isHijiritsuNotMeireiI($current)
    {
        return $current['partOfSpeech2'] === 'hijiritsu' &&
            $current['inflectionForm'] !== 'meireiI';
    }
}
