<?php

namespace Limelight;

use Limelight\Config\Setup;
use Limelight\Parse\Parser;
use Limelight\Config\Config;
use Limelight\Parse\Tokenizer;
use Limelight\Parse\TokenParser;

class Limelight
{
    /**
     * @var implements Limelight\Mecab\Mecab
     */
    private $mecab;

    /**
     * Boot.
     */
    public function __construct()
    {
        $setup = new Setup();

        $setup->boot();

        $config = Config::getInstance();

        $this->mecab = $config->make('Limelight\Mecab\Mecab');
    }

    /**
     * Parse the given text.
     *
     * @param string $text
     *
     * @return Limelight\Classes\LimelightResults
     */
    public function parse($text)
    {
        $tokenizer = new Tokenizer();

        $tokenParser = new TokenParser();

        $parser = new Parser($this->mecab, $tokenizer, $tokenParser);

        return $parser->handle($text);
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
}
