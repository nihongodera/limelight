<?php

namespace Limelight\Mecab\PhpMecab;

use Limelight\Mecab\Mecab;

class PhpMecab implements Mecab
{
    /**
     * MeCab.
     *
     * @var Limelight\Mecab\Mecab
     */
    private $mecab;

    /**
     * Options array pulled from config.php.
     *
     * @var array
     */
    private $options;

    /**
     * Construct.
     *
     * @param array $options
     */
    public function __construct($options)
    {
        $this->options = $options;

        $this->mecab = $this->makeMecab();
    }

    /**
     * Split string into nodes.
     *
     * @param string $string
     *
     * @return Node
     */
    public function parseToNode($string)
    {
        $node = $this->mecab->parseToNode($string);

        return new PhpMecabNode($node);
    }

    /**
     * Split string into nodes, return raw Mecab node.
     *
     * @param string $string
     *
     * @return Mecab_Node
     */
    public function parseToMecabNode($string)
    {
        return $this->mecab->parseToNode($string);
    }

    /**
     * Parse string, return mecab results as string.
     *
     * @param string $string
     *
     * @return string
     */
    public function parseToString($string)
    {
        return $this->mecab->parseToString($string);
    }

    /**
     * Make instance of MeCab_Tagger.
     *
     * @return MeCab_Tagger
     */
    private function makeMecab()
    {
        $options = $this->buildOptions();

        $mecab = new \MeCab\Tagger($options);

        return $mecab;
    }

    /**
     * Build options array for constructor.
     *
     * @return array
     */
    private function buildOptions()
    {
        $options = [];

        foreach ($this->options as $flag => $value) {
            if (!is_null($value) && !empty($value) && $value !== '') {
                array_push($options, $flag, $value);
            }
        }

        return $options;
    }
}
