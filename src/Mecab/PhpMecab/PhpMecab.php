<?php

declare(strict_types=1);

namespace Limelight\Mecab\PhpMecab;

use MeCab\Tagger;
use Limelight\Mecab\Node;
use Limelight\Mecab\Mecab;
use MeCab\Node as MecabNode;

class PhpMecab implements Mecab
{
    private Tagger $mecab;

    /**
     * Options array pulled from config.php.
     */
    private array $options;

    public function __construct(array $options)
    {
        $this->options = $options;

        $this->mecab = $this->makeMecab();
    }

    /**
     * Split string into nodes.
     */
    public function parseToNode(string $string): Node
    {
        $node = $this->mecab->parseToNode($string);

        return new PhpMecabNode($node);
    }

    /**
     * Split string into nodes, return raw Mecab node.
     */
    public function parseToMecabNode(string $string): MecabNode
    {
        return $this->mecab->parseToNode($string);
    }

    /**
     * Parse string, return mecab results as string.
     */
    public function parseToString(string $string): string
    {
        return $this->mecab->parseToString($string);
    }

    /**
     * Make instance of MeCab_Tagger.
     */
    private function makeMecab(): Tagger
    {
        $options = $this->buildOptions();

        return new Tagger($options);
    }

    /**
     * Build options array for constructor.
     */
    private function buildOptions(): array
    {
        $options = [];

        foreach ($this->options as $flag => $value) {
            if (!empty($value)) {
                array_push($options, $flag, $value);
            }
        }

        return $options;
    }
}
