<?php

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Parse\PartOfSpeech\POSRegistry;
use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class Meishi implements PartOfSpeech
{
    /**
     * POS's that can use other POS class.
     *
     * @var array
     */
    private $overrides = [
        'sahensetsuzoku'    => 'fukushikanou',
        'keiyoudoushigokan' => 'fukushikanou',
        'naikeiyoushigokan' => 'fukushikanou',
        'tokushu'           => 'hijiritsu',
    ];

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
        $properties['partOfSpeech'] = 'noun';

        $registry = POSRegistry::getInstance();

        if (array_key_exists($current['partOfSpeech2'], $this->overrides)) {
            $className = 'Meishi'.ucfirst($this->overrides[$current['partOfSpeech2']]);
        } else {
            $className = 'Meishi'.ucfirst($current['partOfSpeech2']);
        }

        if (class_exists('Limelight\\Parse\\PartOfSpeech\\Classes\\'.$className)) {
            $POSClass = $registry->getClass($className);

            $properties = $POSClass->handle($properties, $previousWord, $previous, $current, $next);
        }

        return $properties;
    }
}
