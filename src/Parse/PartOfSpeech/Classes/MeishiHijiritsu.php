<?php

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class MeishiHijiritsu implements PartOfSpeech
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
            $method = strtolower($current['partOfSpeech3']);

            if (method_exists($this, $method)) {
                $properties = $this->$method($properties, $previous, $current, $next);
            }
        }

        return $properties;
    }

    /**
     * Handle fukushikanou.
     *
     * @param array $properties
     * @param array $previous
     * @param array $current
     * @param array $next
     *
     * @return array
     */
    private function fukushikanou($properties, $previous, $current, $next)
    {
        if ($next['partOfSpeech1'] === 'joshi' && $next['literal'] === 'に') {
            $properties['partOfSpeech'] = 'adverb';

            $properties['eatNext'] = true;
        }

        return $properties;
    }

    /**
     * Handle jodoushigokan.
     *
     * @param array $properties
     * @param array $previous
     * @param array $current
     * @param array $next
     *
     * @return array
     */
    private function jodoushigokan($properties, $previous, $current, $next)
    {
        if ($next['inflectionType'] === 'tokushuDa') {
            $properties['partOfSpeech'] = 'verb';

            $properties['grammar'] = 'auxillary';

            if ($next['inflectionForm'] === 'taigensetsuzoku') {
                $properties['eatNext'] = true;
            }
        } elseif ($this->nextIsJoshiAndFukushika($next)) {
            $properties['partOfSpeech'] = 'adverb';

            $properties['eatNext'] = true;
        }

        return $properties;
    }

    /**
     * Return true if next POS1 is joshi and next POS2 is fukushika.
     *
     * @param array $next
     *
     * @return bool
     */
    protected function nextIsJoshiAndFukushika($next)
    {
        return $next['partOfSpeech1'] === 'joshi' &&
            $next['partOfSpeech2'] === 'fukushika';
    }

    /**
     * Handle keiyoudoushigokan for partOfSpeech3.
     *
     * @param array $properties
     * @param array $previous
     * @param array $current
     * @param array $next
     *
     * @return array
     */
    private function keiyoudoushigokan($properties, $previous, $current, $next)
    {
        $properties['partOfSpeech'] = 'adjective';

        if ($this->nextIsTokushuDaOrRentaika($next)) {
            $properties['eatNext'] = true;
        }

        return $properties;
    }

    /**
     * Return true if next inflection is tokushuDa and inflection form is
     * taigensetsuzoku or if POS2 is rentaika.
     *
     * @param array $next
     *
     * @return bool
     */
    protected function nextIsTokushuDaOrRentaika($next)
    {
        return (
            $next['inflectionType'] === 'tokushuDa' &&
            $next['inflectionForm'] === 'taigensetsuzoku'
        ) || $next['partOfSpeech2'] === 'rentaika';
    }
}
