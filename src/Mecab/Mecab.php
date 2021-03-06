<?php

namespace Limelight\Mecab;

use Mecab\Node;

interface Mecab
{
    /**
     * Split string into nodes.
     *
     * @param string $string
     * @return Node
     */
    public function parseToNode($string);

    /**
     * Split string into nodes, return raw Mecab node.
     *
     * @param string $string
     * @return Node
     */
    public function parseToMecabNode($string);

    /**
     * Parse string, return mecab results as string.
     *
     * @param string $string
     * @return string
     */
    public function parseToString($string);
}
