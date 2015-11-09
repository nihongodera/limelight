<?php

namespace Limelight\Providers\Laravel;

use Limelight\Limelight;
use Illuminate\Support\ServiceProvider;

class LimelightServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
           __DIR__.'/../../config.php' => config_path('limelight.php'),
        ]);
    }

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
