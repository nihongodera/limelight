<?php

namespace Limelight\Providers\Laravel;

use Limelight\Limelight;
use Illuminate\Support\ServiceProvider;

class LimelightServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Limelight\Limelight', function ($app) {
            return new Limelight();
        });
    }
}
