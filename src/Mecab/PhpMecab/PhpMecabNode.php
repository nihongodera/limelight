<?php

declare(strict_types=1);

namespace Limelight\Mecab\PhpMecab;

use Limelight\Mecab\Node;
use Mecab\Node as MecabNode;

class PhpMecabNode implements Node
{
    private ?MecabNode $node;

    public function __construct(MecabNode $node)
    {
        $this->node = $node;
    }

    /**
     * Get the next node.
     */
    public function getNext(): ?Node
    {
        if (is_null($this->node)) {
            return null;
        }

        $node = $this->node->getNext();

        $this->setNode($node);

        return $this;
    }

    /**
     * Get the node feature.
     */
    public function getFeature(): string
    {
        if (is_null($this->node)) {
            throw new \RuntimeException('Mecab node is null');
        }

        return $this->node->getFeature();
    }

    /**
     * Get the node surface.
     */
    public function getSurface(): string
    {
        if (is_null($this->node)) {
            throw new \RuntimeException('Mecab node is null');
        }

        return $this->node->getSurface();
    }

    /**
     * Set the node on the object.
     */
    public function setNode(?MecabNode $node): Node
    {
        $this->node = $node;

        return $this;
    }

    /**
     * Get node off object.
     */
    public function getNode(): ?MecabNode
    {
        return $this->node;
    }
}
