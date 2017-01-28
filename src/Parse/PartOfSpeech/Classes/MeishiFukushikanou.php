<?php

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class MeishiFukushikanou implements PartOfSpeech
{
    /**
     * Handle the parsing request.
     *
     * @param array $properties
     * @param array $previousWord [previous word]
     * @param array $previous     [previous token]
     * @param array $current      [current token]
     * @param array $next         [next token]
     *
     * @return array
     */
    public function handle(array $properties, $previousWord, $previous, array $current, $next)
    {
        if ($next) {
            if ($next['inflectionType'] === 'sahenSuru') {
                $properties['partOfSpeech'] = 'verb';

                $properties['eatNext'] = true;
            } elseif ($this->isTokushuDaNotSahensetsuzoku($current, $next)) {
                $properties['partOfSpeech'] = 'adjective';

                if ($next['inflectionForm'] === 'taigensetsuzoku') {
                    $properties['eatNext'] = true;

                    $properties['eatLemma'] = false;
                }
            } elseif ($next['inflectionType'] === 'tokushuNai') {
                $properties['partOfSpeech'] = 'adjective';

                $properties['eatNext'] = true;
            } elseif ($next['partOfSpeech1'] === 'joshi' && $next['literal'] === 'に') {
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
     * @param array $current
     * @param array $next
     *
     * @return bool
     */
    public function isTokushuDaNotSahensetsuzoku($current, $next)
    {
        return $next['inflectionType'] = 'tokushuDa' &&
            $current['partOfSpeech2'] !== 'sahensetsuzoku';
    }
}
