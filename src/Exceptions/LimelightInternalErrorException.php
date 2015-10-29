<?php

namespace Limelight\Exceptions;

class LimelightInternalErrorException extends \Exception
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
    public function __construct($message)
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
        return __CLASS__.": {$this->message}\n";
    }
}
