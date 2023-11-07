<?php

declare(strict_types=1);

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Classes\LimelightWord;
use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class Jodoushi implements PartOfSpeech
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

        if ($this->isInInflections($previousToken, $currentToken)) {
            $properties['attachToPrevious'] = true;
        } elseif ($this->isFuhenkagataAndNorU($currentToken)) {
            $properties['attachToPrevious'] = true;
        } elseif ($this->isNaraAndPreviousIsMeishi($previousToken, $currentToken)) {
            $properties['partOfSpeech'] = 'conjunction';
        } elseif ($this->isTokushuAndNotNa($currentToken)) {
            $properties['partOfSpeech'] = 'verb';
        }

        return $properties;
    }

    /**
     * Return true if no previous or if previous exists, POS2 is not kakarijoshi and is in inflections.
     */
    protected function isInInflections(?array $previousToken, array $currentToken): bool
    {
        $inflections = [
            'tokushuTa',
            'tokushuNai',
            'tokushuTai',
            'tokushuMasu',
            'tokushuNu',
        ];

        return is_null($previousToken) ||
            ($previousToken['partOfSpeech2'] !== 'kakarijoshi' &&
                in_array($currentToken['inflectionType'], $inflections, true));
    }

    /**
     * Return true if inflection is fuhenkagata and lemma is ん or う.
     */
    protected function isFuhenkagataAndNorU(array $currentToken): bool
    {
        return $currentToken['inflectionType'] === 'fuhenkagata' &&
            ($currentToken['lemma'] === 'ん' || $currentToken['lemma'] === 'う');
    }

    /**
     * Return true if literal is なら and previous POS is meishi.
     */
    protected function isNaraAndPreviousIsMeishi(?array $previousToken, array $currentToken): bool
    {
        return $currentToken['literal'] === 'なら' &&
            $previousToken &&
            $previousToken['partOfSpeech1'] === 'meishi';
    }

    /**
     * Return true if inflection is tokushu and literal is not な.
     */
    protected function isTokushuAndNotNa(array $currentToken): bool
    {
        return ($currentToken['inflectionType'] === 'tokushuDa' ||
            $currentToken['inflectionType'] === 'tokushuDesu') &&
            $currentToken['literal'] !== 'な';
    }
}
