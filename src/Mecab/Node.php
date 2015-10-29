<?php

namespace Limelight\Mecab;

interface Node
{
    /**
     * Get the next node.
     *
     * @return this
     */
    public function getNext();

    /**
     * Get the node feature.
     *
     * @return string
     */
    public function getFeature();

    /**
     * Get the node surface.
     *
     * @return string
     */
    public function getSurface();

    /**
     * Set the node on the object.
     *
     * @param MeCab_Node $node
     */
    public function setNode($node);

    /**
     * Get node off object.
     *
     * @return MeCab_Node
     */
    public function getNode();
}
