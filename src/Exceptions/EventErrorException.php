<?php

namespace Limelight\Exceptions;

class EventErrorException extends LimelightException
{
    /**
     * @var string
     */
    protected $message;

    /**
     * Construct.
     *
     * @param string $message
     */
    public function __construct($message = 'Event error.')
    {
        $this->message = $message;

        parent::__construct($message);
    }

    /**
     * How to display error.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->handle();
    }
}
