<?php

namespace Limelight\Config;

use Dotenv\Dotenv;

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
        }

        $dotenv = new Dotenv($envPath);

        $dotenv->load();
    }
}
