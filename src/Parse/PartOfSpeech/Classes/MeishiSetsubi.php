<?php

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class MeishiSetsubi implements PartOfSpeech
{
    /**
     * Handle the parsing request.
     *
     * @param array $properties
     * @param array $previousWord
     * @param array $previousToken
     * @param array $currentToken
     * @param array $nextToken
     * @return array
     */
    public function handle(
        array $properties,
        $previousWord,
        $previousToken,
        array $currentToken,
        $nextToken
    ) {
        if ($currentToken['partOfSpeech3'] === 'jinmei') {
            $properties['partOfSpeech'] = 'suffix';
        } elseif ($previousToken && $previousToken['partOfSpeech2'] === 'kazu') {
            $properties['partOfSpeech'] = 'suffix';
        } else {
            if ($currentToken['partOfSpeech3'] === 'tokushu' && $currentToken['lemma'] === 'さ') {
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
