<?php

declare(strict_types=1);

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Classes\LimelightWord;
use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class Joshi implements PartOfSpeech
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
        $properties['partOfSpeech'] = 'postposition';

        if ($this->isSetsuzokujishi($currentToken)) {
            $properties['attachToPrevious'] = true;
        }

        return $properties;
    }

    /**
     * Return true if POS2 is setsuzokujishi and literal is て, で, or ば.
     */
    public function isSetsuzokujishi(array $currentToken): bool
    {
        return $currentToken['partOfSpeech2'] === 'setsuzokujoshi' &&
            in_array($currentToken['literal'], ['て', 'で', 'ば']);
    }
}
