<?php

declare(strict_types=1);

namespace Limelight\Mecab;

use Mecab\Node as MecabNode;

interface Node
{
    /**
     * Get the next node.
     */
    public function getNext(): ?Node;

    /**
     * Get the node feature.
     */
    public function getFeature(): string;

    /**
     * Get the node surface.
     */
    public function getSurface(): string;

    /**
     * Set the node on the object.
     */
    public function setNode(?MecabNode $node): Node;

    /**
     * Get node off object.
     */
    public function getNode(): ?MecabNode;
}
