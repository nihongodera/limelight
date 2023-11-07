<?php

declare(strict_types=1);

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Classes\LimelightWord;
use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class MeishiSetsubi implements PartOfSpeech
{
    /**
     * Handle the parsing request.
     */
    public function handle(
        array $properties,
        ?LimelightWord $previousWord,
        ?array $previousToken,
        array $currentToken,
        ?array $nextToken
    ): array {
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
