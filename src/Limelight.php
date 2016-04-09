<?php

namespace Limelight;

use Limelight\Parse\Parser;
use Limelight\Config\Config;
use Limelight\Parse\NoParser;
use Limelight\Parse\Tokenizer;
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
     * Boot.
     */
    public function __construct()
    {
        $config = Config::getInstance();

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

        $tokenParser = new TokenParser();

        $parser = new Parser($this->mecab, $tokenizer, $tokenParser);

        return $parser->handle($text, $runPlugins);
    }

    /**
     * Run given text through plugins without mecab parsing. Kanji input will fail.
     *
     * @param string $text
     * @param array  $pluginWhiteList [Plugins to run]
     *
     * @return Limelight\Classes\LimelightResults/ InvalidInputException
     */
    public function noParse($text, $pluginWhiteList = ['Romaji'])
    {
        $noParser = new NoParser();

        return $noParser->handle($text, $pluginWhiteList);
    }

    /**
     * Dynamically set config values.
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
}
