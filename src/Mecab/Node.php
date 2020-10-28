<?php

namespace Limelight\Mecab;

use Mecab\Node as MecabNode;

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
     * @param MecabNode $node
     */
    public function setNode($node);

    /**
     * Get node off object.
     *
     * @return MecabNode
     */
    public function getNode();
}
