<?php

declare(strict_types=1);

namespace Limelight\Helpers\Contracts;

interface Jsonable
{
    /**
     * Convert the object to its JSON representation.
     */
    public function toJson(int $options = 0): string;
}
