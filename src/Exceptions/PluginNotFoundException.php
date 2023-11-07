<?php

declare(strict_types=1);

namespace Limelight\Exceptions;

class PluginNotFoundException extends LimelightException
{
    /**
     * @var string
     */
    protected $message;

    public function __construct(string $message = 'Requested plugin not found.')
    {
        $this->message = $message;

        parent::__construct($message);
    }

    /**
     * How to display error.
     */
    public function __toString(): string
    {
        return $this->handle();
    }
}
