<?php

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class Jodoushi implements PartOfSpeech
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

        if ($this->isInInflections($previousToken, $currentToken)) {
            $properties['attachToPrevious'] = true;
        } elseif ($this->isFuhenkagataAndNorU($currentToken)) {
            $properties['attachToPrevious'] = true;
        } elseif ($this->isNaraAndPreviousIsMeishi($previousToken, $currentToken)) {
            $properties['partOfSpeech'] = 'conjunction';
        } elseif ($this->isTokushuAndNa($currentToken)) {
            $properties['partOfSpeech'] = 'verb';
        }

        return $properties;
    }

    /**
     * Return true if previous exists, POS is kakarijoshi and is in inflections
     * array.
     *
     * @param array $previousToken
     * @param array $currentToken
     * @return bool
     */
    protected function isInInflections($previousToken, $currentToken)
    {
        $inflections = [
            'tokushuTa',
            'tokushuNai',
            'tokushuTai',
            'tokushuMasu',
            'tokushuNu',
        ];

        return is_null($previousToken) ||
            (!is_null($previousToken) &&
                $previousToken['partOfSpeech2'] !== 'kakarijoshi') &&
                in_array($currentToken['inflectionType'], $inflections
            );
    }

    /**
     * Return true if inflection is fuhenkagata and lemma is ん or う.
     *
     * @param array $currentToken
     * @return bool
     */
    protected function isFuhenkagataAndNorU($currentToken)
    {
        return $currentToken['inflectionType'] === 'fuhenkagata' &&
            ($currentToken['lemma'] === 'ん' || $currentToken['lemma'] === 'う');
    }

    /**
     * Return true if literal is なら and previous POS is meishi.
     *
     * @param array $previousToken
     * @param array $currentToken
     * @return bool
     */
    protected function isNaraAndPreviousIsMeishi($previousToken, $currentToken)
    {
        return $currentToken['literal'] === 'なら' &&
            $previousToken &&
            $previousToken['partOfSpeech1'] === 'meishi';
    }

    /**
     * Return true inflection is tokushu and literal is な.
     *
     * @param array $currentToken
     * @return bool
     */
    protected function isTokushuAndNa($currentToken)
    {
        return $currentToken['inflectionType'] === 'tokushuDa' ||
            $currentToken['inflectionType'] === 'tokushuDesu' &&
            $currentToken['literal'] !== 'な';
    }
}
