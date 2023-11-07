<?php

declare(strict_types=1);

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Classes\LimelightWord;
use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class MeishiHijiritsu implements PartOfSpeech
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

        $method = strtolower($currentToken['partOfSpeech3']);

        if (method_exists($this, $method)) {
            $properties = $this->$method($properties, $previousToken, $currentToken, $nextToken);
        }

        return $properties;
    }

    /**
     * Handle fukushikanou.
     */
    private function fukushikanou(
        array $properties,
        ?array $previousToken,
        array $currentToken,
        array $nextToken
    ): array {
        if ($nextToken['partOfSpeech1'] === 'joshi' && $nextToken['literal'] === 'に') {
            $properties['partOfSpeech'] = 'adverb';

            $properties['eatNext'] = true;
        }

        return $properties;
    }

    /**
     * Handle jodoushigokan.
     */
    private function jodoushigokan(
        array $properties,
        ?array $previousToken,
        array $currentToken,
        array $nextToken
    ): array {
        if ($nextToken['inflectionType'] === 'tokushuDa') {
            $properties['partOfSpeech'] = 'verb';

            $properties['grammar'] = 'auxillary';

            if ($nextToken['inflectionForm'] === 'taigensetsuzoku') {
                $properties['eatNext'] = true;
            }
        } elseif ($this->nextIsJoshiAndFukushika($nextToken)) {
            $properties['partOfSpeech'] = 'adverb';

            $properties['eatNext'] = true;
        }

        return $properties;
    }

    /**
     * Return true if next POS1 is joshi and next POS2 is fukushika.
     */
    protected function nextIsJoshiAndFukushika(array $nextToken): bool
    {
        return $nextToken['partOfSpeech1'] === 'joshi' &&
            $nextToken['partOfSpeech2'] === 'fukushika';
    }

    /**
     * Handle keiyoudoushigokan for partOfSpeech3.
     */
    private function keiyoudoushigokan(
        array $properties,
        ?array $previousToken,
        array $currentToken,
        array $nextToken
    ): array {
        $properties['partOfSpeech'] = 'adjective';

        if ($this->nextIsTokushuDaOrRentaika($nextToken)) {
            $properties['eatNext'] = true;
        }

        return $properties;
    }

    /**
     * Return true if next inflection is tokushuDa and inflection form is
     * taigensetsuzoku or if POS2 is rentaika.
     */
    protected function nextIsTokushuDaOrRentaika(array $nextToken): bool
    {
        return (
            $nextToken['inflectionType'] === 'tokushuDa' &&
            $nextToken['inflectionForm'] === 'taigensetsuzoku'
        ) || $nextToken['partOfSpeech2'] === 'rentaika';
    }
}
