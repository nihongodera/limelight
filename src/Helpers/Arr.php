<?php

declare(strict_types=1);

namespace Limelight\Helpers;

use Limelight\Classes\Collection;
use Limelight\Helpers\Contracts\Jsonable;
use Limelight\Helpers\Contracts\Arrayable;

/**
 * Methods in this trait adapted from Laravel Arr class.
 *
 * @link https://github.com/illuminate/collections/blob/master/Arr.php
 */
trait Arr
{
    /**
     * Determine whether the given value is array accessible.
     */
    public function arrAccessible($value): bool
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }

    /**
     * Collapse an array of arrays into a single array.
     */
    public function arrCollapse(array $array): array
    {
        $results = [];

        foreach ($array as $values) {
            if ($values instanceof Collection) {
                $values = $values->all();
            } elseif (!is_array($values)) {
                continue;
            }

            $results[] = $values;
        }

        return array_merge([], ...$results);
    }

    /**
     * Get all of the given array except for a specified array of keys.
     *
     * @param array|string|int|float $keys
     */
    public function arrExcept(array $array, $keys): array
    {
        $this->arrForget($array, $keys);

        return $array;
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param \ArrayAccess|array $array
     * @param string|int|float   $key
     */
    public function arrExists($array, $key): bool
    {
        if ($array instanceof \ArrayAccess) {
            return $array->offsetExists($key);
        }

        if (is_float($key)) {
            $key = (string) $key;
        }

        return array_key_exists($key, $array);
    }

    /**
     * Return the first element in an array passing a given truth test.
     */
    protected function arrFirst(array $array, ?callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return $this->value($default);
            }

            foreach ($array as $item) {
                return $item;
            }

            return $this->value($default);
        }
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }

        return $this->value($default);
    }

    /**
     * Flatten a multi-dimensional array into a single level.
     */
    public function arrFlatten(array $array, int $depth = PHP_INT_MAX): array
    {
        $result = [];

        foreach ($array as $item) {
            $item = $item instanceof Collection ? $item->all() : $item;

            if (!is_array($item)) {
                $result[] = $item;
            } else {
                $values = $depth === 1
                    ? array_values($item)
                    : $this->arrFlatten($item, $depth - 1);

                foreach ($values as $value) {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @param array|string|int|float $keys
     */
    public function arrForget(array &$array, $keys): void
    {
        $original = &$array;

        $keys = (array) $keys;

        if (count($keys) === 0) {
            return;
        }

        foreach ($keys as $key) {
            if ($this->arrExists($array, $key)) {
                unset($array[$key]);

                continue;
            }

            $parts = explode('.', $key);

            $array = &$original;

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (isset($array[$part]) && $this->arrAccessible($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }

            unset($array[array_shift($parts)]);
        }
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param \ArrayAccess|array $array
     * @param string|int|null    $key
     */
    public function arrGet($array, $key, $default = null)
    {
        if (!$this->arrAccessible($array)) {
            return $this->value($default);
        }

        if (is_null($key)) {
            return $array;
        }

        if ($this->arrExists($array, $key)) {
            return $array[$key];
        }

        if (!str_contains($key, '.')) {
            return $array[$key] ?? $this->value($default);
        }

        foreach (explode('.', $key) as $segment) {
            if ($this->arrAccessible($array) && $this->arrExists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return $this->value($default);
            }
        }

        return $array;
    }

    /**
     * Return the last element in an array passing a given truth test.
     */
    public function arrLast(array $array, ?callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            return empty($array) ? $this->value($default) : end($array);
        }

        return $this->arrFirst(array_reverse($array, true), $callback, $default);
    }

    /**
     * Get a subset of the items from the given array.
     */
    public function arrOnly(array $array, $keys): array
    {
        return array_intersect_key($array, array_flip((array) $keys));
    }

    /**
     * Pluck an array of values from an array.
     *
     * @param string|array|int|null $value
     * @param string|array|null     $key
     */
    public function arrPluck(array $array, $value, $key = null): array
    {
        $results = [];

        [$value, $key] = $this->explodePluckParameters($value, $key);

        foreach ($array as $item) {
            $itemValue = $this->dataGet($item, $value);

            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = $this->dataGet($item, $key);

                if (is_object($itemKey) && method_exists($itemKey, '__toString')) {
                    $itemKey = (string) $itemKey;
                }

                $results[$itemKey] = $itemValue;
            }
        }

        return $results;
    }

    /**
     * Push an item onto the beginning of an array.
     */
    public function arrPrepend(array $array, $value, $key = null): array
    {
        if (func_num_args() === 2) {
            array_unshift($array, $value);
        } else {
            $array = [$key => $value] + $array;
        }

        return $array;
    }

    /**
     * Get a value from the array, and remove it.
     *
     * @param string|int $key
     */
    public function arrPull(array &$array, $key, $default = null)
    {
        $value = $this->arrGet($array, $key, $default);

        $this->arrForget($array, $key);

        return $value;
    }

    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param string|array $key
     */
    protected function dataGet($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        foreach ($key as $i => $segment) {
            unset($key[$i]);

            if (is_null($segment)) {
                return $target;
            }

            if ($segment === '*') {
                if ($target instanceof Collection) {
                    $target = $target->all();
                } elseif (!is_array($target)) {
                    return $this->value($default);
                }

                $result = [];

                foreach ($target as $item) {
                    $result[] = $this->dataGet($item, $key);
                }

                return in_array('*', $key, true) ? $this->arrCollapse($result) : $result;
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
     * Results array of items from Collection or Arrayable.
     */
    protected function getArrayableItems($items): array
    {
        if (is_array($items)) {
            return $items;
        }
        if ($items instanceof Collection) {
            return $items->all();
        }
        if ($items instanceof Arrayable) {
            return $items->toArray();
        }
        if ($items instanceof Jsonable) {
            return json_decode($items->toJson(), true, 512, JSON_THROW_ON_ERROR);
        }
        if ($items instanceof \JsonSerializable) {
            return $items->jsonSerialize();
        }
        if ($items instanceof \Traversable) {
            return iterator_to_array($items);
        }

        return (array) $items;
    }

    /**
     * Explode the "value" and "key" arguments passed to "pluck".
     */
    protected function explodePluckParameters($value, $key): array
    {
        $value = is_string($value) ? explode('.', $value) : $value;

        $key = is_null($key) || is_array($key) ? $key : explode('.', $key);

        return [$value, $key];
    }

    /**
     * Determine if the given value is callable, but not a string.
     */
    protected function useAsCallable($value): bool
    {
        return !is_string($value) && is_callable($value);
    }

    /**
     * Return the default value of the given value.
     */
    protected function value($value, ...$args)
    {
        return $value instanceof \Closure ? $value(...$args) : $value;
    }

    /**
     * Get a value retrieving callback.
     *
     * @param callable|string|null $value
     */
    protected function valueRetriever($value): callable
    {
        if ($this->useAsCallable($value)) {
            return $value;
        }

        return fn ($item) => $this->dataGet($item, $value);
    }
}
