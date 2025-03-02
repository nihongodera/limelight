<?php

declare(strict_types=1);

namespace Limelight\Providers\Laravel;

use Limelight\Limelight;
use Illuminate\Support\ServiceProvider;

class LimelightServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config.php' => config_path('limelight.php'),
        ]);
    }

    /**
     * Register bindings in the container.
     */
    public function register(): void
    {
        $this->app->singleton(Limelight::class, function () {
            return new Limelight();
        });
    }
}
