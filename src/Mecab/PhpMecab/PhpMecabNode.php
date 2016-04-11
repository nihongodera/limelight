<?php

namespace Limelight\Mecab\PhpMecab;

use Limelight\Mecab\Node;

class PhpMecabNode implements Node
{
    /**
     * @var MeCab_Node
     */
    private $node;

    /**
     * Construct.
     *
     * @param MeCab_Node $node
     */
    public function __construct(\MeCab\Node $node)
    {
        $this->node = $node;
    }

    /**
     * Get the next node.
     *
     * @return this
     */
    public function getNext()
    {
        if (is_null($this->node)) {
            return;
        }

        $node = $this->node->getNext();

        $this->setNode($node);

        return $this;
    }

    /**
     * Get the node feature.
     *
     * @return string
     */
    public function getFeature()
    {
        return $this->node->getFeature();
    }

    /**
     * Get the node surface.
     *
     * @return string
     */
    public function getSurface()
    {
        return $this->node->getSurface();
    }

    /**
     * Set the node on the object.
     *
     * @param MeCab_Node $node
     */
    public function setNode($node)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * Get node off object.
     *
     * @return MeCab_Node
     */
    public function getNode()
    {
        return $this->node;
    }
}
