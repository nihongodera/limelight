<?php

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class MeishiFukushikanou implements PartOfSpeech
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
            if ($nextToken['inflectionType'] === 'sahenSuru') {
                $properties['partOfSpeech'] = 'verb';

                $properties['eatNext'] = true;
            } elseif ($this->isTokushuDaNotSahensetsuzoku($currentToken, $nextToken)) {
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
        }

        return $properties;
    }

    /**
     * Return true if next inflection is tokushuDa and POS2 is not
     * sahensetsuzoku.
     *
     * @param array $currentToken
     * @param array $nextToken
     * @return bool
     */
    public function isTokushuDaNotSahensetsuzoku($currentToken, $nextToken)
    {
        return $nextToken['inflectionType'] = 'tokushuDa' &&
            $currentToken['partOfSpeech2'] !== 'sahensetsuzoku';
    }
}
