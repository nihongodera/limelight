<?php

declare(strict_types=1);

namespace Limelight\Mecab;

use Mecab\Node as MecabNode;

interface Mecab
{
    /**
     * Split string into nodes.
     */
    public function parseToNode(string $string): Node;

    /**
     * Split string into nodes, return raw Mecab node.
     */
    public function parseToMecabNode(string $string): MecabNode;

    /**
     * Parse string, return mecab results as string.
     */
    public function parseToString(string $string): string;
}
