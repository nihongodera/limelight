<?php

declare(strict_types=1);

namespace Limelight\Parse;

use Limelight\Limelight;
use Limelight\Events\Dispatcher;
use Limelight\Helpers\PluginHelper;
use Limelight\Classes\LimelightWord;
use Limelight\Helpers\JapaneseHelpers;
use Limelight\Classes\LimelightResults;
use Limelight\Exceptions\InvalidInputException;

class NoParser
{
    use JapaneseHelpers;
    use PluginHelper;

    private Limelight $limelight;

    private Dispatcher $dispatcher;

    public function __construct(Limelight $limelight, Dispatcher $dispatcher)
    {
        $this->limelight = $limelight;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handle the no-parse for given text.
     *
     * @throws InvalidInputException
     */
    public function handle(string $text, array $pluginWhiteList): LimelightResults
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
     */
    private function buildToken(string $text): array
    {
        return [
            'literal'       => $text,
            'lemma'         => $text,
            'reading'       => $text,
            'pronunciation' => $text,
        ];
    }

    /**
     * Build array on full properties.
     */
    private function buildProperties(): array
    {
        return [
            'partOfSpeech' => null,
            'grammar'      => null,
        ];
    }
}
