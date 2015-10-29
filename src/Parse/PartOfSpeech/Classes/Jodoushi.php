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

        $inflections = [
            'tokushuTa',
            'tokushuNai',
            'tokushuTai',
            'tokushuMasu',
            'tokushuNu',
        ];

        if (is_null($previous) || (!is_null($previous) && $previous['partOfSpeech2'] !== 'kakarijoshi') && in_array($current['inflectionType'], $inflections)) {
            $properties['attachToPrevious'] = true;
        } elseif ($current['inflectionType'] === 'fuhenkagata' && ($current['lemma'] === 'ん' || $current['lemma'] === 'う')) {
            $properties['attachToPrevious'] = true;
        } elseif ($current['literal'] === 'なら' && $previous['partOfSpeech1'] === 'meishi') {
            $properties['partOfSpeech'] = 'conjunction';
        } elseif ($current['inflectionType'] === 'tokushuDa' || $current['inflectionType'] === 'tokushuDesu' && $current['literal'] !== 'な') {
            $properties['partOfSpeech'] = 'verb';
        }

        return $properties;
    }
}
