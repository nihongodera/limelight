<?php

namespace Limelight\Parse\PartOfSpeech;

use Limelight\Exceptions\InternalErrorException;

class POSRegistry
{
    /**
     * Instance of self.
     *
     * @var self
     */
    private static $instance;

    /**
     * Part of speech classes.
     *
     * @var array
     */
    private $classes = [];

    /**
     * Private construct.
     */
    private function __construct()
    {
    }

    /**
     * Get instance of self.
     *
     * @return self
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get class form registry.
     *
     * @param string $className
     *
     * @throws InternalErrorException
     *
     * @return PartOfSpeech
     */
    public function getClass($className)
    {
        $fullName = 'Limelight\\Parse\\PartOfSpeech\\Classes\\'.$className;

        if (isset($this->classes[$fullName])) {
            return $this->classes[$fullName];
        } elseif ($this->validateClass($fullName)) {
            return $this->setClass($fullName);
        }

        throw new InternalErrorException(
            "Class {$fullName} could not be instantiated."
        );
    }

    /**
     * Set class in registry.
     *
     * @param string $fullName
     *
     * @return PartOfSpeech
     */
    public function setClass($fullName)
    {
        $this->validateClass($fullName);

        $newClass = new $fullName();

        $this->classes[$fullName] = $newClass;

        return $newClass;
    }

    /**
     * Validate class.
     *
     * @param string $class
     *
     * @throws InternalErrorException
     *
     * @return bool/InternalErrorException
     */
    private function validateClass($class)
    {
        if (!class_exists($class)) {
            throw new InternalErrorException("Class {$class} does not exist.");
        }

        return true;
    }
}
