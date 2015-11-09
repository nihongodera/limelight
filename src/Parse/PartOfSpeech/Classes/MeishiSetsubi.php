<?php

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class MeishiSetsubi implements PartOfSpeech
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
        if ($current['partOfSpeech3'] === 'jinmei') {
            $properties['partOfSpeech'] = 'suffix';
        } elseif ($previous['partOfSpeech2'] === 'kazu') {
            $properties['partOfSpeech'] = 'suffix';
        } else {
            if ($current['partOfSpeech3'] === 'tokushu' && $current['lemma'] === 'さ') {
                $properties['updatePOS'] = true;
                $properties['partOfSpeech'] = 'noun';
            } else {
                $properties['alsoAttachToLemma'] = true;
            }

            $properties['attachToPrevious'] = true;
        }

        return $properties;
    }
}
