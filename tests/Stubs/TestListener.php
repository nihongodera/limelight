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
        $path = __DIR__.'/test.log';

        if ($payload instanceof LimelightWord) {
            $message = 'WordWasCreated fired. '.$payload->word();

            return file_put_contents($path, $message, FILE_APPEND);
        } elseif ($payload instanceof LimelightResults) {
            $message = 'ParseWasSuccessful fired.'.$payload->string('word');

            return file_put_contents($path, $message, FILE_APPEND);
        }

        return (is_null($payload) ? 'It works!' : "Payload says {$payload}");
    }
}
