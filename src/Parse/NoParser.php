<?php

namespace Limelight\Parse;

use Limelight\Classes\LimelightResults;
use Limelight\Classes\LimelightWord;
use Limelight\Events\Dispatcher;
use Limelight\Exceptions\InvalidInputException;
use Limelight\Helpers\JapaneseHelpers;
use Limelight\Helpers\PluginHelper;
use Limelight\Limelight;

class NoParser
{
    use PluginHelper;
    use JapaneseHelpers;

    /**
     * @var Limelight
     */
    private $limelight;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Construct.
     *
     * @param Limelight  $limelight
     * @param Dispatcher $dispatcher
     */
    public function __construct(Limelight $limelight, Dispatcher $dispatcher)
    {
        $this->limelight = $limelight;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handle the no-parse for given text.
     *
     * @param string $text
     * @param array $pluginWhiteList
     * @throws InvalidInputException
     * @return LimelightResults
     */
    public function handle($text, array $pluginWhiteList)
    {
        if ($this->hasKanji($text)) {
            throw new InvalidInputException('Text must not contain kanji.');
        }

        $token = $this->buildToken($text);

        $properties = $this->buildProperties();

        $words = [new LimelightWord($token, $properties, $this->limelight)];

        $this->dispatcher->fire('WordWasCreated', $words[0]);

        $pluginResults = $this->runPlugins($text, null, $token, $words, $pluginWhiteList);

        $results = new LimelightResults($text, $words, $pluginResults);

        $this->dispatcher->fire('ParseWasSuccessful', $results);

        return $results;
    }

    /**
     * Build token using raw text for all properties.
     *
     * @param string $text
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
