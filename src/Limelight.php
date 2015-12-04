<?php

namespace Limelight;

use Limelight\Parse\Parser;
use Limelight\Config\Config;
use Limelight\Parse\NoParser;
use Limelight\Parse\Tokenizer;
use Limelight\Parse\TokenParser;

class Limelight
{
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
     *
     * @return Limelight\Classes\LimelightResults/ InvalidInputException
     */
    public function noParse($text)
    {
        $noParser = new NoParser();

        return $noParser->handle($text);
    }

    /**
     * MeCab parseToNode method. Returns native Limelight node object.
     *
     * @param string $string
     *
     * @return Limelight\Mecab\Node
     */
    public function mecabToNode($string)
    {
        return $this->mecab->parseToNode($string);
    }

    /**
     * MeCab parseToNode method. Returns raw Mecab node object.
     *
     * @param string $string
     *
     * @return Mecab_Node
     */
    public function mecabToMecabNode($string)
    {
        return $this->mecab->parseToMecabNode($string);
    }

    /**
     * MeCab parseToString method.
     *
     * @param string $string
     *
     * @return string
     */
    public function mecabToString($string)
    {
        return $this->mecab->parseToString($string);
    }

    /**
     * MeCab split method.
     *
     * @param string $string
     *
     * @return array
     */
    public function mecabSplit($string)
    {
        return $this->mecab->split($string);
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
