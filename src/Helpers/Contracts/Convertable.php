<?php

declare(strict_types=1);

namespace Limelight\Helpers\Contracts;

interface Convertable
{
    /**
     * Convert the instance items to format.
     */
    public function convert(string $format);
}
