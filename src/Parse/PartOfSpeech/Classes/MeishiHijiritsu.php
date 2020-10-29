<?php

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class MeishiHijiritsu implements PartOfSpeech
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
        if ($nextToken) {
            $method = strtolower($currentToken['partOfSpeech3']);

            if (method_exists($this, $method)) {
                $properties = $this->$method($properties, $previousToken, $currentToken, $nextToken);
            }
        }

        return $properties;
    }

    /**
     * Handle fukushikanou.
     *
     * @param array $properties
     * @param array $previousToken
     * @param array $currentToken
     * @param array $nextToken
     * @return array
     */
    private function fukushikanou($properties, $previousToken, $currentToken, $nextToken)
    {
        if ($nextToken['partOfSpeech1'] === 'joshi' && $nextToken['literal'] === 'に') {
            $properties['partOfSpeech'] = 'adverb';

            $properties['eatNext'] = true;
        }

        return $properties;
    }

    /**
     * Handle jodoushigokan.
     *
     * @param array $properties
     * @param array $previousToken
     * @param array $currentToken
     * @param array $nextToken
     * @return array
     */
    private function jodoushigokan($properties, $previousToken, $currentToken, $nextToken)
    {
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
     *
     * @param array $nextToken
     * @return bool
     */
    protected function nextIsJoshiAndFukushika($nextToken)
    {
        return $nextToken['partOfSpeech1'] === 'joshi' &&
            $nextToken['partOfSpeech2'] === 'fukushika';
    }

    /**
     * Handle keiyoudoushigokan for partOfSpeech3.
     *
     * @param array $properties
     * @param array $previousToken
     * @param array $currentToken
     * @param array $nextToken
     * @return array
     */
    private function keiyoudoushigokan($properties, $previousToken, $currentToken, $nextToken)
    {
        $properties['partOfSpeech'] = 'adjective';

        if ($this->nextIsTokushuDaOrRentaika($nextToken)) {
            $properties['eatNext'] = true;
        }

        return $properties;
    }

    /**
     * Return true if next inflection is tokushuDa and inflection form is
     * taigensetsuzoku or if POS2 is rentaika.
     *
     * @param array $nextToken
     * @return bool
     */
    protected function nextIsTokushuDaOrRentaika($nextToken)
    {
        return (
            $nextToken['inflectionType'] === 'tokushuDa' &&
            $nextToken['inflectionForm'] === 'taigensetsuzoku'
        ) || $nextToken['partOfSpeech2'] === 'rentaika';
    }
}
