<?php

namespace Limelight\Exceptions;

class InvalidInputException extends LimelightException
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
    public function __construct($message = 'Invalid input.')
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
