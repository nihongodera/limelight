<?php

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class Joshi implements PartOfSpeech
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
        $properties['partOfSpeech'] = 'postposition';

        if ($this->isSetsuzokujishi($current)) {
            $properties['attachToPrevious'] = true;
        }

        return $properties;
    }

    /**
     * Return true if POS is setsuzokujishi and literal is て, で, or ば.
     *
     * @param array $current
     *
     * @return bool
     */
    public function isSetsuzokujishi($current)
    {
        return $current['partOfSpeech2'] === 'setsuzokujoshi' &&
            in_array($current['literal'], ['て', 'で', 'ば']);
    }
}
