<?php

namespace Limelight;

use Limelight\Parse\Parser;
use Limelight\Config\Config;
use Limelight\Parse\NoParser;
use Limelight\Parse\Tokenizer;
use Limelight\Events\Dispatcher;
use Limelight\Parse\TokenParser;

class Limelight
{
    use MecabMethods;

    /**
     * Mecab instance.
     *
     * @var Limelight\Mecab\Mecab
     */
    private $mecab;

    /**
     * Dispatcher for eventing.
     *
     * @var Limelight\Events\Dispatcher
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
     * @param bool   $runPlugins [When false, plugins do not run]
     *
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
     * @param array  $pluginWhiteList [Plugins to run]
     * @param bool   $supressEvents   [When true, events will not be fired]
     *
     * @return Limelight\Classes\LimelightResults/ InvalidInputException
     */
    public function noParse($text, $pluginWhiteList = ['Romaji'], $supressEvents = false)
    {
        $this->dispatcher->toggleEvents($supressEvents);

        $noParser = new NoParser($this, $this->dispatcher);

        $results = $noParser->handle($text, $pluginWhiteList);

        $this->dispatcher->toggleEvents($supressEvents);

        return $results;
    }

    /**
     * Dynamically set config values. Could be dangerous, be careful.
     *
     * @param string $value
     * @param string $key1
     * @param string $key1
     *
     * @return bool
     */
    public function setConfig($value, $key1, $key2)
    {
        $config = Config::getInstance();

        $config->set($value, $key1, $key2);
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
