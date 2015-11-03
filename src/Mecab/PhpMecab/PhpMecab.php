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
     * Console flags for php-mecab constructor options.
     *
     * @var array
     */
    private $flags = [
        'dictionary' => '-d',
    ];

    /**
     * Construct.
     *
     * @param  array $options
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
     * @return Limelight\Mecab\Node
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
     * Split string into array.
     *
     * @param string $string
     *
     * @return array
     */
    public function split($string)
    {
        if (isset($this->options['dictionary'])) {
            return $this->mecab->split($string, $this->options['dictionary']);
        }

        return $this->mecab->split($string);
    }

    /**
     * Return dictionary file.
     *
     * @return string
     */
    public function getDictionary()
    {
        return $this->options['dictionary'];
    }

    /**
     * Make instance of MeCab_Tagger.
     *
     * @return MeCab_Tagger
     */
    private function makeMecab()
    {
        $options = $this->buildOptions();

        return new \MeCab_Tagger($options);
    }

    /**
     * Build options array for constructor.
     *
     * @return array
     */
    private function buildOptions()
    {
        $options = [];

        foreach ($this->options as $option => $value) {
            if (!is_null($value) && !empty($value) && $value !== '') {
                $flag = $this->setFlag($option);

                if ($flag) {
                    $options[] = $flag;
                }

                $options[] = $value;
            }
        }

        return $options;
    }

    /**
     * Find console flags in $this->flags.
     *
     * @param string $option
     *
     * @return mixed
     */
    private function setFlag($option)
    {
        if (array_key_exists($option, $this->flags)) {
            return $this->flags[$option];
        } elseif (!is_numeric($option)) {
            return $option;
        }

        return;
    }
}
