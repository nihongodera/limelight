<?php

namespace Limelight\Parse\PartOfSpeech;

use Limelight\Exceptions\LimelightInternalErrorException;

class POSRegistry
{
    /**
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
     * @return class_instance
     */
    public function getClass($className)
    {
        $fullName = 'Limelight\\Parse\\PartOfSpeech\\Classes\\'.$className;

        if (isset($this->classes[$fullName])) {
            return $this->classes[$fullName];
        } elseif (class_exists($fullName)) {
            return $this->setClass($fullName);
        }

        throw new LimelightInternalErrorException("Class {$fullName} does not exist.");
    }

    /**
     * Set class in registry.
     *
     * @param string $fullName
     *
     * @return class_instance
     */
    public function setClass($fullName)
    {
        $newClass = new $fullName();

        $this->classes[$fullName] = $newClass;

        return $newClass;
    }
}
