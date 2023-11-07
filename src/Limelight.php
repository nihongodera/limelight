<?php

declare(strict_types=1);

namespace Limelight;

use Limelight\Mecab\Mecab;
use Limelight\Parse\Parser;
use Limelight\Config\Config;
use Limelight\Parse\NoParser;
use Limelight\Parse\Tokenizer;
use Limelight\Events\Dispatcher;
use Limelight\Parse\TokenParser;
use Limelight\Classes\LimelightResults;

class Limelight
{
    use MecabMethods;

    private Mecab $mecab;

    /**
     * Dispatcher for eventing.
     */
    private Dispatcher $dispatcher;

    public function __construct()
    {
        $config = Config::getInstance();

        $this->dispatcher = new Dispatcher($config->get('listeners'));

        $this->mecab = $config->make(Mecab::class);
    }

    /**
     * Parse the given text.
     */
    public function parse(string $text, bool $runPlugins = true): LimelightResults
    {
        $tokenizer = new Tokenizer();

        $tokenParser = new TokenParser($this, $this->dispatcher);

        $parser = new Parser($this->mecab, $tokenizer, $tokenParser, $this->dispatcher);

        return $parser->handle($text, $runPlugins);
    }

    /**
     * Run given text through plugins without mecab parsing. Kanji input will fail.
     */
    public function noParse(string $text, array $pluginWhiteList = ['Romaji'], bool $suppressEvents = false): LimelightResults
    {
        $this->dispatcher->toggleEvents($suppressEvents);

        $noParser = new NoParser($this, $this->dispatcher);

        $results = $noParser->handle($text, $pluginWhiteList);

        $this->dispatcher->toggleEvents($suppressEvents);

        return $results;
    }

    /**
     * Dynamically set config values. Could be dangerous, be careful.
     */
    public function setConfig(string $value, string $key1, string $key2): bool
    {
        return Config::getInstance()->set($value, $key1, $key2);
    }

    /**
     * Get the attached dispatcher instance.
     */
    public function dispatcher(): Dispatcher
    {
        return $this->dispatcher;
    }
}
