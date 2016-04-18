<?php

namespace Limelight\Tests\Stubs;

use Limelight\Config\Config;
use Limelight\Classes\LimelightWord;
use Limelight\Classes\LimelightResults;
use Limelight\Events\LimelightListener;

class TestListener implements LimelightListener
{
    public function handle($payload)
    {
        $config = Config::getInstance();

        if ($payload instanceof LimelightWord) {
            $config->resetConfig();

            return;
        } elseif ($payload instanceof LimelightResults) {
            $config->resetConfig();

            $config->set(['Limelight\Tests\Stubs\TestListener'], 'listeners', 'WordWasCreated');

            return;
        }

        return (is_null($payload) ? 'It works!' : "Payload says {$payload}");
    }
}
