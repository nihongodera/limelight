<?php

declare(strict_types=1);

namespace Limelight\Events;

interface LimelightListener
{
    /**
     * Respond to event.
     */
    public function handle($payload);
}
