<?php

namespace Limelight\Classes;

use ArrayAccess;
use Limelight\Helpers\Arr;
use Limelight\Classes\LimelightResults;

abstract class Collection implements ArrayAccess
{
    use Arr;

    /**
     * Collection methods adopted from Laravel Collection.
     * https://github.com/illuminate/support/blob/master/Collection.php
     */
    
    /**
     * Get all words.
     *
     * @return $this
     */
    public function all()
    {
        return $this->words;
    }
    
    /**
     * Count the number of items on the object.
     *
     * @return int
     */
    public function count()
    {
        return count($this->words);
    }

    /**
     * Run a filter over each of the items.
     *
     * @param  callable|null  $callback
     *
     * @return static
     */
    public function filter(callable $callback = null)
    {
        if ($callback) {
            $return = [];

            foreach ($this->words as $key => $value) {
                if ($callback($value, $key)) {
                    $return[$key] = $value;
                }
            }
            return new static($this->text, $return, $this->pluginData);
        }

        return new static($this->text, array_filter($this->words), $this->pluginData);
    }

    /**
     * Get the first item from the collection.
     *
     * @param  callable|null  $callback
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function first(callable $callback = null, $default = null)
    {
        return $this->arrFirst($this->words, $callback, $default);
    }

    /**
     * Concatenate values of a given key as a string.
     *
     * @param  string  $value
     * @param  string  $glue
     *
     * @return string
     */
    public function implode($value, $glue = null)
    {
        $first = $this->first();

        if (is_array($first) || is_object($first)) {
            return implode($glue, $this->pluck($value)->all());
        }

        return implode($value, $this->words);
    }

    /**
     * Determine if the collection is empty or not.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->words);
    }
    
    /**
     * Run a map over each of the items.
     *
     * @param  callable  $callback
     *
     * @return static
     */
    public function map(callable $callback)
    {
        $keys = array_keys($this->words);

        $items = array_map($callback, $this->words, $keys);

        return new static($this->text, array_combine($keys, $items), $this->pluginData);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed  $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->words[$key];
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed  $key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->words);
    }

    /**
     * Set the item at a given offset.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->words[] = $value;
        } else {
            $this->words[$key] = $value;
        }
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  string  $key
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->words[$key]);
    }

    /**
     * Get the values of a given key.
     *
     * @param  string  $value
     * @param  string|null  $key
     *
     * @return static
     */
    public function pluck($value, $key = null)
    {
        return new static($this->text, $this->arrPluck($this->words, $value, $key), $this->pluginData);
    }

    /**
     * Push an item onto the end of the collection.
     *
     * @param  mixed  $value
     *
     * @return $this
     */
    public function push($value)
    {
        $this->offsetSet(null, $value);

        return $this;
    }

    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param  mixed   $target
     * @param  string|array  $key
     * @param  mixed   $default
     * @return mixed
     */
    protected function dataGet($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        while (($segment = array_shift($key)) !== null) {
            if ($segment === '*') {
                if ($target instanceof LimelightResults) {
                    $target = $target->all();
                } elseif (! is_array($target)) {
                    return value($default);
                }

                $result = $this->arrPluck($target, $key);

                return in_array('*', $key) ? $this->arrCollapse($result) : $result;
            }
            if ($this->arrAccessible($target) && $this->arrExists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return $this->value($default);
            }
        }

        return $target;
    }

    /**
     * Explode the "value" and "key" arguments passed to "pluck".
     *
     * @param  string|array  $value
     * @param  string|array|null  $key
     *
     * @return array
     */
    protected function explodePluckParameters($value, $key)
    {
        $value = is_string($value) ? explode('.', $value) : $value;

        $key = is_null($key) || is_array($key) ? $key : explode('.', $key);

        return [$value, $key];
    }

    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}
