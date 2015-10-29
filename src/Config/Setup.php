<?php

namespace Limelight\Config;

class Setup
{
    /**
     * Boot Limelight.
     */
    public function boot()
    {
        $this->loadEnv();
    }

    /**
     * Load dotenv.
     */
    private function loadEnv()
    {
        if (file_exists('.env')) {
            $envPath = dirname('.env');

            \Dotenv::load($envPath);
        }
    }
}
