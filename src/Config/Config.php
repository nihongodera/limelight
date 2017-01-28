<?php

namespace Limelight\Config;

use Limelight\Exceptions\InvalidInputException;
use Limelight\Exceptions\InternalErrorException;

class Config
{
    /**
     * config.php.
     *
     * @var array
     */
    private $configFile;

    /**
     * Instance of self.
     *
     * @var self
     */
    private static $instance;

    /**
     * Private construct.
     */
    private function __construct()
    {
        $this->resetConfig();
    }

    /**
     * Get value from config file.
     *
     * @param string $string [config.php key]
     *
     * @throws InvalidInputException
     *
     * @return mixed
     */
    public function get($string)
    {
        if (isset($this->configFile[$string])) {
            return $this->configFile[$string];
        }

        throw new InvalidInputException("Index {$string} does not exist in config.php.");
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
     * Get registered plugins from config file.
     *
     * @return array
     */
    public function getPlugins()
    {
        return $this->get('plugins');
    }

    /**
     * Make class instance from given interface name.  Class must be bound to
     * interface in config.php.
     *
     * @param string $interface [full interface namespace]
     *
     * @throws InternalErrorException
     *
     * @return class_instance
     */
    public function make($interface)
    {
        $bindings = $this->get('bindings');

        $fullClassName = $this->getFullClassName($bindings, $interface);

        $this->validateClass($fullClassName, $interface);

        $classOptions = $this->getClassOptions($fullClassName);

        set_error_handler(function () {
            throw new \Exception();
        });

        try {
            $instance = new $fullClassName($classOptions);

            restore_error_handler();

            return $instance;
        } catch (\Exception $e) {
            throw new InternalErrorException(
                "Class {$fullClassName} could not be instantiated."
            );
        }
    }

    /**
     * Reset config values to those defined in config file.
     */
    public function resetConfig()
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
     * @param string $value
     * @param string $key1
     * @param string $key1
     *
     * @throws InvalidInputException
     *
     * @return bool
     */
    public function set($value, $key1, $key2 = null)
    {
        if (isset($this->configFile[$key1]) && isset($this->configFile[$key1][$key2])) {
            $this->configFile[$key1][$key2] = $value;

            return true;
        } elseif (isset($this->configFile[$key1]) && is_null($key2)) {
            $this->configFile[$key1] = $value;

            return true;
        }

        throw new InvalidInputException('Key not found in config file.');
    }

    /**
     * Erase config value entirely.
     *
     * @param string $key1
     * @param string $key2
     */
    public function erase($key1, $key2)
    {
        if (isset($this->configFile[$key1]) && isset($this->configFile[$key1][$key2])) {
            unset($this->configFile[$key1][$key2]);
        }
    }

    /**
     * Get full class namespace from bindings array.
     *
     * @param array  $bindings
     * @param string $interface
     *
     * @throws InternalErrorException
     *
     * @return string
     */
    private function getFullClassName(array $bindings, $interface)
    {
        if (isset($bindings[$interface])) {
            return $bindings[$interface];
        } else {
            throw new InternalErrorException(
                "Cannot resolve interface {$interface}. Check config.php file bindings."
            );
        }
    }

    /**
     * Validate the class before instantiating.
     *
     * @param string $fullClassName
     * @param string $interface
     *
     * @throws InternalErrorException
     */
    private function validateClass($fullClassName, $interface)
    {
        if (!class_exists($fullClassName)) {
            throw new InternalErrorException(
                "Class {$fullClassName} defined in config.php does not exist."
            );
        }

        if (!in_array($interface, class_implements($fullClassName))) {
            throw new InternalErrorException(
                "Class {$fullClassName} does not implement interface {$interface}."
            );
        }
    }

    /**
     * Get options for class.
     *
     * @param string $fullClassName
     *
     * @return array
     */
    private function getClassOptions($fullClassName)
    {
        $shortClassName = $this->getShortClassName($fullClassName);

        $options = $this->get('options');

        $classOptions = (isset($options[$shortClassName]) ? $options[$shortClassName] : []);

        return $classOptions;
    }

    /**
     * Get short class name from full namespace.
     *
     * @param string $fullClassName
     *
     * @return string
     */
    private function getShortClassName($fullClassName)
    {
        $class = new \ReflectionClass($fullClassName);

        return $class->getShortName();
    }
}
