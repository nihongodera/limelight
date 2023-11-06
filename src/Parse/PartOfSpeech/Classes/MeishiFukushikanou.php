<?php

declare(strict_types=1);

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Classes\LimelightWord;
use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class MeishiFukushikanou implements PartOfSpeech
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
        if (!$nextToken) {
            return $properties;
        }

        if ($nextToken['inflectionType'] === 'sahenSuru') {
            $properties['partOfSpeech'] = 'verb';

            $properties['eatNext'] = true;
        } elseif ($this->isTokushuDaDesuNotSahensetsuzoku($currentToken, $nextToken)) {
            $properties['partOfSpeech'] = 'adjective';

            if ($nextToken['inflectionForm'] === 'taigensetsuzoku') {
                $properties['eatNext'] = true;

                $properties['eatLemma'] = false;
            }
        } elseif ($nextToken['inflectionType'] === 'tokushuNai') {
            $properties['partOfSpeech'] = 'adjective';

            $properties['eatNext'] = true;
        } elseif ($nextToken['partOfSpeech1'] === 'joshi' && $nextToken['literal'] === 'に') {
            $properties['partOfSpeech'] = 'adverb';

            $properties['eatNext'] = false;
        }

        return $properties;
    }

    /**
     * Return true if next inflection is tokushuDa/Desu and POS2 is not
     * sahensetsuzoku.
     */
    public function isTokushuDaDesuNotSahensetsuzoku(array $currentToken, array $nextToken): bool
    {
        return ($nextToken['inflectionType'] === 'tokushuDa' || $nextToken['inflectionType'] === 'tokushuDesu') &&
            $currentToken['partOfSpeech2'] !== 'sahensetsuzoku';
    }
}
