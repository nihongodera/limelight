<?php

declare(strict_types=1);

namespace Limelight;

use Limelight\Mecab\Node;
use Mecab\Node as MecabNode;

trait MecabMethods
{
    /**
     * MeCab parseToNode method. Returns native Limelight node object.
     */
    public function mecabToNode(string $string): Node
    {
        return $this->mecab->parseToNode($string);
    }

    /**
     * MeCab parseToMecabNode method. Returns raw Mecab node object.
     */
    public function mecabToMecabNode(string $string): MecabNode
    {
        return $this->mecab->parseToMecabNode($string);
    }

    /**
     * MeCab parseToString method.
     */
    public function mecabToString(string $string): string
    {
        return $this->mecab->parseToString($string);
    }
}
