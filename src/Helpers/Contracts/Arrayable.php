<?php

declare(strict_types=1);

namespace Limelight\Helpers\Contracts;

interface Arrayable
{
    /**
     * Get the instance as an array.
     */
    public function toArray(): array;
}
