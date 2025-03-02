<?php

declare(strict_types=1);

namespace Limelight\Parse\PartOfSpeech;

use Limelight\Exceptions\InternalErrorException;

class POSRegistry
{
    private static POSRegistry $instance;

    /**
     * Part of speech classes.
     */
    private array $classes = [];

    private function __construct()
    {
    }

    public static function getInstance(): POSRegistry
    {
        if (!isset(self::$instance)) {
            static::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get class from registry.
     *
     * @throws InternalErrorException
     */
    public function getClass(string $className): PartOfSpeech
    {
        $fullName = 'Limelight\\Parse\\PartOfSpeech\\Classes\\'.$className;

        if (isset($this->classes[$fullName])) {
            return $this->classes[$fullName];
        }
        if ($this->validateClass($fullName)) {
            return $this->setClass($fullName);
        }

        throw new InternalErrorException(
            "Class {$fullName} could not be instantiated."
        );
    }

    /**
     * Set class in registry.
     */
    public function setClass(string $fullName): PartOfSpeech
    {
        $this->validateClass($fullName);

        $newClass = new $fullName();

        $this->classes[$fullName] = $newClass;

        return $newClass;
    }

    /**
     * Validate class.
     *
     * @throws InternalErrorException
     */
    private function validateClass(string $class): bool
    {
        if (!class_exists($class)) {
            throw new InternalErrorException("Class {$class} does not exist.");
        }

        return true;
    }
}
