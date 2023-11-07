<?php

declare(strict_types=1);

namespace Limelight\Parse\PartOfSpeech\Classes;

use Limelight\Classes\LimelightWord;
use Limelight\Parse\PartOfSpeech\POSRegistry;
use Limelight\Parse\PartOfSpeech\PartOfSpeech;

class Meishi implements PartOfSpeech
{
    /**
     * POS's that can use other POS class.
     */
    private array $overrides = [
        'sahensetsuzoku'    => 'fukushikanou',
        'keiyoudoushigokan' => 'fukushikanou',
        'naikeiyoushigokan' => 'fukushikanou',
        'tokushu'           => 'hijiritsu',
    ];

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
        $properties['partOfSpeech'] = 'noun';

        $registry = POSRegistry::getInstance();

        if (array_key_exists($currentToken['partOfSpeech2'], $this->overrides)) {
            $className = 'Meishi'.ucfirst($this->overrides[$currentToken['partOfSpeech2']]);
        } else {
            $className = 'Meishi'.ucfirst($currentToken['partOfSpeech2']);
        }

        if (class_exists('Limelight\\Parse\\PartOfSpeech\\Classes\\'.$className)) {
            $POSClass = $registry->getClass($className);

            $properties = $POSClass->handle($properties, $previousWord, $previousToken, $currentToken, $nextToken);
        }

        return $properties;
    }
}
