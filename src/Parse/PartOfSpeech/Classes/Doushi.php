<?php

declare(strict_types=1);

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Classes\LimelightWord;
use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class Doushi implements PartOfSpeech
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
        $properties['partOfSpeech'] = 'verb';

        if ($currentToken['partOfSpeech2'] === 'setsubi') {
            $properties['attachToPrevious'] = true;
        } elseif ($this->isHijiritsuNotMeireiI($currentToken)) {
            $properties['attachToPrevious'] = true;
        }

        return $properties;
    }

    /**
     * Return true if POS2 is hijiritsu and inflection is not meireiI.
     */
    protected function isHijiritsuNotMeireiI(array $currentToken): bool
    {
        return $currentToken['partOfSpeech2'] === 'hijiritsu' &&
            $currentToken['inflectionForm'] !== 'meireiI';
    }
}
