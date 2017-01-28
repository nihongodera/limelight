<?php

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class Jodoushi implements PartOfSpeech
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
        $properties['partOfSpeech'] = 'postposition';

        if ($this->isInInflections($previous, $current)) {
            $properties['attachToPrevious'] = true;
        } elseif ($this->isFuhenkagataAndNorU($current)) {
            $properties['attachToPrevious'] = true;
        } elseif ($this->isNaraAndPreviousIsMeishi($previous, $current)) {
            $properties['partOfSpeech'] = 'conjunction';
        } elseif ($this->isTokushuAndNa($current)) {
            $properties['partOfSpeech'] = 'verb';
        }

        return $properties;
    }

    /**
     * Return true if previous exists, POS is kakarijoshi and is in inflections
     * array.
     *
     * @param array $previous
     * @param array $current
     *
     * @return bool
     */
    protected function isInInflections($previous, $current)
    {
        $inflections = [
            'tokushuTa',
            'tokushuNai',
            'tokushuTai',
            'tokushuMasu',
            'tokushuNu',
        ];

        return is_null($previous) ||
            (!is_null($previous) &&
                $previous['partOfSpeech2'] !== 'kakarijoshi') &&
                in_array($current['inflectionType'], $inflections
            );
    }

    /**
     * Return true if inflection is fuhenkagata and lemma is ん or う.
     *
     * @param array $current
     *
     * @return bool
     */
    protected function isFuhenkagataAndNorU($current)
    {
        return $current['inflectionType'] === 'fuhenkagata' &&
            ($current['lemma'] === 'ん' || $current['lemma'] === 'う');
    }

    /**
     * Return true if literal is なら and previous POS is meishi.
     *
     * @param array $previous
     * @param array $current
     *
     * @return bool
     */
    protected function isNaraAndPreviousIsMeishi($previous, $current)
    {
        return $current['literal'] === 'なら' &&
            $previous['partOfSpeech1'] === 'meishi';
    }

    /**
     * Return true inflection is tokushu and literal is な.
     *
     * @param array $current
     *
     * @return bool
     */
    protected function isTokushuAndNa($current)
    {
        return $current['inflectionType'] === 'tokushuDa' ||
            $current['inflectionType'] === 'tokushuDesu' &&
            $current['literal'] !== 'な';
    }
}
