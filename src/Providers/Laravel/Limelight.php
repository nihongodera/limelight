<?php

declare(strict_types=1);

namespace Limelight\Providers\Laravel;

use Illuminate\Support\Facades\Facade;

class Limelight extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \Limelight\Limelight::class;
    }
}
