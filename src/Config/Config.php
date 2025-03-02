<?php

declare(strict_types=1);

namespace Limelight\Config;

use Limelight\Exceptions\InvalidInputException;
use Limelight\Exceptions\InternalErrorException;

class Config
{
    private array $configFile;

    private static Config $instance;

    private function __construct()
    {
        $this->resetConfig();
    }

    /**
     * Get value from config file.
     *
     * @throws InvalidInputException
     */
    public function get(string $string)
    {
        if (isset($this->configFile[$string])) {
            return $this->configFile[$string];
        }

        throw new InvalidInputException("Index {$string} does not exist in config.php.");
    }

    public static function getInstance(): Config
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get registered plugins from config file.
     */
    public function getPlugins(): array
    {
        return $this->get('plugins');
    }

    /**
     * Make class instance from given interface name.  Class must be bound to
     * interface in config.php.
     *
     * @template T
     *
     * @param class-string<T> $interface
     *
     * @throws InternalErrorException
     *
     * @return T
     */
    public function make(string $interface)
    {
        $bindings = $this->get('bindings');

        $fullClassName = $this->getFullClassName($bindings, $interface);

        $this->validateClass($fullClassName, $interface);

        $classOptions = $this->getClassOptions($fullClassName);

        set_error_handler(static function () {
            throw new \RuntimeException();
        });

        try {
            $instance = new $fullClassName($classOptions);

            restore_error_handler();

            return $instance;
        } catch (\RuntimeException $e) {
            throw new InternalErrorException(
                "Class {$fullClassName} could not be instantiated."
            );
        }
    }

    /**
     * Reset config values to those defined in config file.
     */
    public function resetConfig(): void
    {
        if (function_exists('config') && config('limelight') !== null) {
            $this->configFile = config('limelight');
        } else {
            $this->configFile = include dirname(__DIR__).'/config.php';
        }
    }

    /**
     * Dynamically set config values.
     *
     * @param array|string $value
     *
     * @throws InvalidInputException
     */
    public function set($value, string $key1, ?string $key2 = null): bool
    {
        if (isset($this->configFile[$key1][$key2])) {
            $this->configFile[$key1][$key2] = $value;

            return true;
        }

        if (isset($this->configFile[$key1]) && is_null($key2)) {
            $this->configFile[$key1] = $value;

            return true;
        }

        throw new InvalidInputException('Key not found in config file.');
    }

    /**
     * Erase config value entirely.
     */
    public function erase(string $key1, string $key2): void
    {
        if (isset($this->configFile[$key1][$key2])) {
            unset($this->configFile[$key1][$key2]);
        }
    }

    /**
     * Get full class namespace from bindings array.
     *
     * @throws InternalErrorException
     */
    private function getFullClassName(array $bindings, string $interface): string
    {
        if (isset($bindings[$interface])) {
            return $bindings[$interface];
        }

        throw new InternalErrorException(
            "Cannot resolve interface {$interface}. Check config.php file bindings."
        );
    }

    /**
     * Validate the class before instantiating.
     *
     * @throws InternalErrorException
     */
    private function validateClass(string $fullClassName, string $interface): void
    {
        if (!class_exists($fullClassName)) {
            throw new InternalErrorException(
                "Class {$fullClassName} defined in config.php does not exist."
            );
        }

        if (!in_array($interface, class_implements($fullClassName), true)) {
            throw new InternalErrorException(
                "Class {$fullClassName} does not implement interface {$interface}."
            );
        }
    }

    /**
     * Get options for class.
     */
    private function getClassOptions(string $fullClassName): array
    {
        $shortClassName = $this->getShortClassName($fullClassName);

        $options = $this->get('options');

        return $options[$shortClassName] ?? [];
    }

    /**
     * Get short class name from full namespace.
     */
    private function getShortClassName(string $fullClassName): string
    {
        return (new \ReflectionClass($fullClassName))->getShortName();
    }
}
