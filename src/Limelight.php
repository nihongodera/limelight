<?php

namespace Limelight;

use Limelight\Config\Config;
use Limelight\Events\Dispatcher;
use Limelight\Parse\NoParser;
use Limelight\Parse\Parser;
use Limelight\Parse\Tokenizer;
use Limelight\Parse\TokenParser;

class Limelight
{
    use MecabMethods;

    /**
     * Mecab instance.
     *
     * @var Mecab
     */
    private $mecab;

    /**
     * Dispatcher for eventing.
     *
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Boot.
     */
    public function __construct()
    {
        $config = Config::getInstance();

        $this->dispatcher = new Dispatcher($config->get('listeners'));

        $this->mecab = $config->make('Limelight\Mecab\Mecab');
    }

    /**
     * Parse the given text.
     *
     * @param string $text
     * @param bool $runPlugins
     * @return Limelight\Classes\LimelightResults
     */
    public function parse($text, $runPlugins = true)
    {
        $tokenizer = new Tokenizer();

        $tokenParser = new TokenParser($this, $this->dispatcher);

        $parser = new Parser($this->mecab, $tokenizer, $tokenParser, $this->dispatcher);

        return $parser->handle($text, $runPlugins);
    }

    /**
     * Run given text through plugins without mecab parsing. Kanji input will fail.
     *
     * @param string $text
     * @param array $pluginWhiteList
     * @param bool $suppressEvents
     * @return Limelight\Classes\LimelightResults
     */
    public function noParse($text, $pluginWhiteList = ['Romaji'], $suppressEvents = false)
    {
        $this->dispatcher->toggleEvents($suppressEvents);

        $noParser = new NoParser($this, $this->dispatcher);

        $results = $noParser->handle($text, $pluginWhiteList);

        $this->dispatcher->toggleEvents($suppressEvents);

        return $results;
    }

    /**
     * Dynamically set config values. Could be dangerous, be careful.
     *
     * @param string $value
     * @param string $key1
     * @param string $key1
     * @return bool
     */
    public function setConfig($value, $key1, $key2)
    {
        return Config::getInstance()->set($value, $key1, $key2);
    }

    /**
     * Get the attached dispatcher instance.
     *
     * @return Dispatcher
     */
    public function dispatcher()
    {
        return $this->dispatcher;
    }
}
