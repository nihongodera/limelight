<?php

namespace Limelight\Providers\Laravel;

use Illuminate\Support\Facades\Facade;

class Limelight extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Limelight\Limelight';
    }
}
