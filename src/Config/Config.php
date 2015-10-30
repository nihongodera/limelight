<?php

namespace Limelight\Config;

use Limelight\Exceptions\LimelightInvalidInputException;
use Limelight\Exceptions\LimelightInternalErrorException;

class Config
{
    /**
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
        $this->configFile = include dirname(__DIR__).'/config.php';
    }

    /**
     * Get value from config file.
     *
     * @param string $string [config.php key]
     *
     * @return mixed
     */
    public function get($string)
    {
        if (isset($this->configFile[$string])) {
            return $this->configFile[$string];
        }

        throw new LimelightInvalidInputException("Index {$string} does not exist in config.php.");
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
        return $this->configFile['plugins'];
    }

    /**
     * Make class instance from given interface name.  Class must be bound to
     * interface in config.php.
     *
     * @param string $interface [full interface namespace]
     *
     * @return class instance
     */
    public function make($interface)
    {
        $bindings = $this->configFile['bindings'];

        $fullClassName = $this->getFullClassName($bindings, $interface);

        $this->validateClass($fullClassName, $interface);

        $classOptions = $this->getClassOptions($fullClassName);

        try {
            $instance = new $fullClassName($classOptions);

            return $instance;
        } catch (\Exception $e) {
            throw new LimelightInternalErrorException("Class {$fullClassName} could not be instantiated.");
        }
    }

    /**
     * Get full class namespace from bindings array.
     *
     * @param array  $bindings
     * @param string $interface
     *
     * @return string
     */
    private function getFullClassName(array $bindings, $interface)
    {
        if (isset($bindings[$interface])) {
            return $bindings[$interface];
        } else {
            throw new LimelightInternalErrorException("Cannot resolve interface {$interface}. Check config.php file bindings.");
        }
    }

    /**
     * Validate the class before instantiating.
     *
     * @param string $fullClassName
     * @param string $interface
     */
    private function validateClass($fullClassName, $interface)
    {
        if (!class_exists($fullClassName)) {
            throw new LimelightInternalErrorException("Class {$fullClassName} defined in config.php does not exist.");
        }

        if (!in_array($interface, class_implements($fullClassName))) {
            throw new LimelightInternalErrorException("Class {$fullClassName} does not implement interface {$interface}.");
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

        $options = $this->configFile['options'];

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
