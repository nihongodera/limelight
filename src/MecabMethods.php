<?php

namespace Limelight;

trait MecabMethods
{
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
