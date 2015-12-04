<?php

namespace Limelight\Parse;

use Limelight\Limelight;
use Limelight\Helpers\Converter;
use Limelight\Helpers\PluginHelper;
use Limelight\Classes\LimelightWord;
use Limelight\Helpers\JapaneseHelpers;
use Limelight\Classes\LimelightResults;
use Limelight\Exceptions\InvalidInputException;

class NoParser
{
    use PluginHelper;
    use JapaneseHelpers;

    /**
     * Handle the no-parse for given text.
     *
     * @param string $text
     * @param array  $pluginWhiteList
     *
     * @return LimelightResults
     */
    public function handle($text, array $pluginWhiteList)
    {
        if ($this->hasKanji($text)) {
            throw new InvalidInputException('Text must not contain kanji.');
        }

        $limelight = new Limelight();

        $converter = new Converter($limelight);

        $token = $this->buildToken($text);

        $properties = $this->buildProperties();

        $words = [new LimelightWord($token, $properties, $converter)];

        $pluginResults = $this->runPlugins($text, null, $token, $words, $pluginWhiteList);

        return new LimelightResults($text, $words, $pluginResults);
    }

    /**
     * Build token using raw text for all properties.
     *
     * @param string $text
     *
     * @return array
     */
    private function buildToken($text)
    {
        return [
            'literal' => $text,
            'lemma' => $text,
            'reading' => $text,
            'pronunciation' => $text,
        ];
    }

    /**
     * Build array on full properties.
     *
     * @return array
     */
    private function buildProperties()
    {
        return [
            'partOfSpeech' => null,
            'grammar' => null,
        ];
    }
}
