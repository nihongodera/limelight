<?php

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Parse\PartOfSpeech\PartOfSpeech;
use Limelight\Parse\PartOfSpeech\POSRegistry;

class Meishi implements PartOfSpeech
{
    /**
     * POS's that can use other POS class.
     *
     * @var array
     */
    private $overrides = [
        'sahensetsuzoku' => 'fukushikanou',
        'keiyoudoushigokan' => 'fukushikanou',
        'naikeiyoushigokan' => 'fukushikanou',
        'tokushu' => 'hijiritsu',
    ];

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
        $properties['partOfSpeech'] = 'noun';

        $registry = POSRegistry::getInstance();

        if (array_key_exists($currentToken['partOfSpeech2'], $this->overrides)) {
            $className = 'Meishi'.ucfirst($this->overrides[$currentToken['partOfSpeech2']]);
        } else {
            $className = 'Meishi'.ucfirst($currentToken['partOfSpeech2']);
        }

        if (class_exists('Limelight\\Parse\\PartOfSpeech\\Classes\\' . $className)) {
            $POSClass = $registry->getClass($className);

            $properties = $POSClass->handle($properties, $previousWord, $previousToken, $currentToken, $nextToken);
        }

        return $properties;
    }
}
