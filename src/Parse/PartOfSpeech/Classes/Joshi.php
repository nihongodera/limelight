<?php

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class Joshi implements PartOfSpeech
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
        $properties['partOfSpeech'] = 'postposition';

        if ($this->isSetsuzokujishi($currentToken)) {
            $properties['attachToPrevious'] = true;
        }

        return $properties;
    }

    /**
     * Return true if POS is setsuzokujishi and literal is て, で, or ば.
     *
     * @param array $currentToken
     * @return bool
     */
    public function isSetsuzokujishi($currentToken)
    {
        return $currentToken['partOfSpeech2'] === 'setsuzokujoshi' &&
            in_array($currentToken['literal'], ['て', 'で', 'ば']);
    }
}
