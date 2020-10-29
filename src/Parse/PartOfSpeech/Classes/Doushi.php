<?php

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class Doushi implements PartOfSpeech
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
        $properties['partOfSpeech'] = 'verb';

        if ($currentToken['partOfSpeech2'] === 'setsubi') {
            $properties['attachToPrevious'] = true;
        } elseif ($this->isHijiritsuNotMeireiI($currentToken)) {
            $properties['attachToPrevious'] = true;
        }

        return $properties;
    }

    /**
     * Return true if POS is hijiritsu and inflection is not meireiI.
     *
     * @param array $currentToken
     * @return bool
     */
    protected function isHijiritsuNotMeireiI($currentToken)
    {
        return $currentToken['partOfSpeech2'] === 'hijiritsu' &&
            $currentToken['inflectionForm'] !== 'meireiI';
    }
}
