<?php

declare(strict_types=1);

namespace Limelight\Classes;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Limelight\Helpers\Arr;
use Limelight\Helpers\Contracts\Arrayable;
use Limelight\Helpers\Contracts\Convertable;
use Limelight\Helpers\Contracts\Jsonable;
use Limelight\Helpers\Converter;

/**
 * Collection methods adapted from Laravel Collection.
 *
 * @link https://github.com/illuminate/collections/blob/master/Collection.php
 */
abstract class Collection implements ArrayAccess, IteratorAggregate, JsonSerializable
{
    use Arr;

    /**
     * Get all words.
     */
    public function all(): array
    {
        return $this->words;
    }

    /**
     * Chunk the collection into chunks of the given size.
     */
    public function chunk(int $size): Collection
    {
        if ($size <= 0) {
            return new static($this->text, [], $this->pluginData);
        }

        $chunks = [];

        foreach (array_chunk($this->words, $size, true) as $chunk) {
            $chunks[] = new static($this->text, $chunk, $this->pluginData);
        }

        return new static($this->text, $chunks, $this->pluginData);
    }

    /**
     * Convert collection to given format.
     */
    public function convert(string $format): Collection
    {
        $converted = [];

        foreach ($this->words as $item) {
            if ($item instanceof Convertable) {
                $converted[] = $item->convert($format);

                continue;
            }

            $converted[] = Converter::convert($item, $format);
        }

        return new static($this->text, $converted, $this->pluginData);
    }

    /**
     * Count the number of items on the object.
     */
    public function count(): int
    {
        return count($this->words);
    }

    /**
     * Get the items in the collection that are not present in the given items.
     */
    public function diff($items): Collection
    {
        return new static(
            $this->text,
            array_diff($this->words, $this->getArrayableItems($items)),
            $this->pluginData
        );
    }

    /**
     * Create a new collection consisting of every n-th element.
     */
    public function nth(int $step, int $offset = 0): Collection
    {
        $new = [];

        $position = 0;

        foreach ($this->slice($offset)->words as $item) {
            if ($position % $step === 0) {
                $new[] = $item;
            }

            $position++;
        }

        return new static($this->text, $new, $this->pluginData);
    }

    /**
     * Get all items except for those with the specified keys.
     */
    public function except($keys): Collection
    {
        if (is_null($keys)) {
            return new static(
                $this->text,
                $this->words,
                $this->pluginData
            );
        }

        if ($keys instanceof Collection) {
            $keys = $keys->all();
        } elseif (!is_array($keys)) {
            $keys = func_get_args();
        }

        return new static(
            $this->text,
            $this->arrExcept($this->words, $keys),
            $this->pluginData
        );
    }

    /**
     * Run a filter over each of the items.
     */
    public function filter(?callable $callback = null): Collection
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
     */
    public function first(?callable $callback = null, $default = null)
    {
        return $this->arrFirst($this->words, $callback, $default);
    }

    /**
     * Get a flattened array of the items in the collection.
     */
    public function flatten(int $depth = PHP_INT_MAX): Collection
    {
        return new static(
            $this->text,
            $this->arrFlatten($this->words, $depth),
            $this->pluginData
        );
    }

    /**
     * Remove an item from the collection by key.
     *
     * @param string|array $keys
     */
    public function forget($keys): Collection
    {
        foreach ($this->getArrayableItems($keys) as $key) {
            $this->offsetUnset($key);
        }

        return $this;
    }

    /**
     * Group an associative array by a field or using a callback.
     */
    public function groupBy($groupBy, bool $preserveKeys = false): Collection
    {
        $groupBy = $this->valueRetriever($groupBy);

        $results = [];

        foreach ($this->words as $key => $value) {
            $groupKeys = $groupBy($value, $key);

            if (!is_array($groupKeys)) {
                $groupKeys = [$groupKeys];
            }

            foreach ($groupKeys as $groupKey) {
                if (is_bool($groupKey)) {
                    $groupKey = (int) $groupKey;
                }
                if ($groupKey instanceof \Stringable) {
                    $groupKey = (string) $groupKey;
                }

                if (!array_key_exists($groupKey, $results)) {
                    $results[$groupKey] = new static($this->text, [], $this->pluginData);
                }

                $results[$groupKey]->offsetSet($preserveKeys ? $key : null, $value);
            }
        }

        return new static($this->text, $results, $this->pluginData);
    }

    /**
     * Concatenate values of a given key as a string.
     */
    public function implode($value, ?string $glue = null): string
    {
        if ($this->useAsCallable($value)) {
            return implode($glue ?? '', $this->map($value)->all());
        }

        $first = $this->first();

        if (is_array($first) || (is_object($first))) {
            return implode($glue ?? '', $this->pluck($value)->all());
        }

        return implode($value ?? '', $this->words);
    }

    /**
     * Intersect the collection with the given items.
     */
    public function intersect($items): Collection
    {
        return new static(
            $this->text,
            array_intersect($this->words, $this->getArrayableItems($items)),
            $this->pluginData
        );
    }

    /**
     * Determine if the collection is empty or not.
     */
    public function isEmpty(): bool
    {
        return empty($this->words);
    }

    /**
     * Get the keys of the collection items.
     */
    public function keys(): Collection
    {
        return new static($this->text, array_keys($this->words), $this->pluginData);
    }

    /**
     * Get the last item from the collection.
     */
    public function last(?callable $callback = null, $default = null)
    {
        return $this->arrLast($this->words, $callback, $default);
    }

    /**
     * Run a map over each of the items.
     */
    public function map(callable $callback): Collection
    {
        $keys = array_keys($this->words);

        $items = array_map($callback, $this->words, $keys);

        return new static($this->text, array_combine($keys, $items), $this->pluginData);
    }

    /**
     * Merge the collection with the given items.
     */
    public function merge($items): Collection
    {
        return new static(
            $this->text,
            array_merge($this->words, $this->getArrayableItems($items)),
            $this->pluginData
        );
    }

    /**
     * Get an item at a given offset.
     */
    public function offsetGet($key)
    {
        return $this->words[$key];
    }

    /**
     * Determine if an item exists at an offset.
     */
    public function offsetExists($key): bool
    {
        return isset($this->words[$key]);
    }

    /**
     * Set the item at a given offset.
     */
    public function offsetSet($key, $value): void
    {
        if (is_null($key)) {
            $this->words[] = $value;
        } else {
            $this->words[$key] = $value;
        }
    }

    /**
     * Unset the item at a given offset.
     */
    public function offsetUnset($key): void
    {
        unset($this->words[$key]);
    }

    /**
     * Get the items with the specified keys.
     */
    public function only($keys): Collection
    {
        if (is_null($keys)) {
            return new static(
                $this->text,
                $this->words,
                $this->pluginData
            );
        }

        if ($keys instanceof Collection) {
            $keys = $keys->all();
        }

        $keys = is_array($keys) ? $keys : func_get_args();

        return new static(
            $this->text,
            $this->arrOnly($this->words, $keys),
            $this->pluginData
        );
    }

    /**
     * Get the values of a given key.
     */
    public function pluck($value, $key = null): Collection
    {
        return new static(
            $this->text,
            $this->arrPluck($this->words, $value, $key),
            $this->pluginData
        );
    }

    /**
     * Get and remove the last N items from the collection.
     */
    public function pop(int $count = 1)
    {
        if ($count === 1) {
            return array_pop($this->words);
        }

        if ($this->isEmpty()) {
            return new static($this->text, $this->words, $this->pluginData);
        }

        $results = [];

        $collectionCount = $this->count();

        foreach (range(1, min($count, $collectionCount)) as $item) {
            $results[] = array_pop($this->words);
        }

        return new static($this->text, $results, $this->pluginData);
    }

    /**
     * Push an item onto the beginning of the collection.
     */
    public function prepend($value, $key = null): Collection
    {
        $this->words = $this->arrPrepend($this->words, ...func_get_args());

        return $this;
    }

    /**
     * Get and remove an item from the collection.
     */
    public function pull($key, $default = null)
    {
        return $this->arrPull($this->words, $key, $default);
    }

    /**
     * Push one or more items onto the end of the collection.
     */
    public function push(...$values): Collection
    {
        foreach ($values as $value) {
            $this->words[] = $value;
        }

        return $this;
    }

    /**
     * Create a collection of all elements that do not pass a given truth test.
     *
     * @param callable|mixed $callback
     */
    public function reject($callback = true): Collection
    {
        $useAsCallable = $this->useAsCallable($callback);

        return $this->filter(function ($value, $key) use ($callback, $useAsCallable) {
            return $useAsCallable
                ? !$callback($value, $key)
                : $value != $callback;
        });
    }

    /**
     * Get and remove the first N items from the collection.
     */
    public function shift(int $count = 1)
    {
        if ($count === 1) {
            return array_shift($this->words);
        }

        if ($this->isEmpty()) {
            return new static($this->text, $this->words, $this->pluginData);
        }

        $results = [];

        $collectionCount = $this->count();

        foreach (range(1, min($count, $collectionCount)) as $item) {
            $results[] = array_shift($this->items);
        }

        return new static($this->text, $results, $this->pluginData);
    }

    /**
     * Slice the underlying collection array.
     */
    public function slice(int $offset, ?int $length = null): Collection
    {
        return new static(
            $this->text,
            array_slice($this->words, $offset, $length, true),
            $this->pluginData
        );
    }

    /**
     * Splice a portion of the underlying collection array.
     */
    public function splice(int $offset, ?int $length = null, $replacement = []): Collection
    {
        if (func_num_args() === 1) {
            return new static(
                $this->text,
                array_splice($this->words, $offset),
                $this->pluginData
            );
        }

        return new static(
            $this->text,
            array_splice($this->words, $offset, $length, $this->getArrayableItems($replacement)),
            $this->pluginData
        );
    }

    /**
     * Take the first or last {$limit} items.
     */
    public function take(int $limit): Collection
    {
        if ($limit < 0) {
            return $this->slice($limit, abs($limit));
        }

        return $this->slice(0, $limit);
    }

    /**
     * Get the collection of items as a plain array.
     */
    public function toArray(): array
    {
        return $this->map(fn ($value) => $value instanceof Arrayable ? $value->toArray() : $value)->all();
    }

    /**
     * Get the collection of items as JSON.
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->jsonSerialize(), JSON_THROW_ON_ERROR | $options);
    }

    /**
     * Transform each item in the collection using a callback.
     */
    public function transform(callable $callback): Collection
    {
        $this->words = $this->map($callback)->all();

        return $this;
    }

    /**
     * Return only unique items from the collection array.
     *
     * @param string|callable|null $key
     */
    public function unique($key = null, $strict = false): Collection
    {
        if (is_null($key) && $strict === false) {
            return new static(
                $this->text,
                array_unique($this->words, SORT_REGULAR),
                $this->pluginData
            );
        }

        $callback = $this->valueRetriever($key);

        $exists = [];

        return $this->reject(function ($item, $key) use ($callback, $strict, &$exists) {
            if (in_array($id = $callback($item, $key), $exists, $strict)) {
                return true;
            }

            $exists[] = $id;
        });
    }

    /**
     * Reset the keys on the underlying array.
     */
    public function values(): Collection
    {
        return new static($this->text, array_values($this->words), $this->pluginData);
    }

    /**
     * Filter items by the given key value pair.
     */
    public function where($key, ?string $operator = null, $value = null): Collection
    {
        return $this->filter($this->operatorForWhere(...func_get_args()));
    }

    /**
     * Zip the collection together with one or more arrays.
     *
     * e.g. new Collection([1, 2, 3])->zip([4, 5, 6]);
     *      => [[1, 4], [2, 5], [3, 6]]
     */
    public function zip($items): Collection
    {
        $arrayableItems = array_map(fn ($items) => $this->getArrayableItems($items), func_get_args());

        $params = array_merge([fn () => new static($this->text, func_get_args(), $this->pluginData), $this->words], $arrayableItems);

        return new static(
            $this->text,
            array_map(...$params),
            $this->pluginData
        );
    }

    /**
     * Get an iterator for the items.
     */
    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->words);
    }

    /**
     * Convert the object into something JSON serializable.
     */
    public function jsonSerialize(): array
    {
        return array_map(static function ($value) {
            if ($value instanceof JsonSerializable) {
                return $value->jsonSerialize();
            }
            if ($value instanceof Jsonable) {
                return json_decode($value->toJson(), true, 512, JSON_THROW_ON_ERROR);
            }
            if ($value instanceof Arrayable) {
                return $value->toArray();
            }

            return $value;
        }, $this->all());
    }

    /**
     * Get an operator checker callback.
     *
     * @param callable|string $key
     */
    protected function operatorForWhere($key, ?string $operator = null, $value = null): \Closure
    {
        if ($this->useAsCallable($key)) {
            return $key;
        }

        if (func_num_args() === 1) {
            $value = true;

            $operator = '=';
        }

        if (func_num_args() === 2) {
            $value = $operator;

            $operator = '=';
        }

        return function ($item) use ($key, $operator, $value) {
            $retrieved = $this->dataGet($item, $key);

            $strings = array_filter([$retrieved, $value], static function ($value) {
                return is_string($value) || (is_object($value) && method_exists($value, '__toString'));
            });

            if (count($strings) < 2 && count(array_filter([$retrieved, $value], 'is_object')) === 1) {
                return in_array($operator, ['!=', '<>', '!==']);
            }

            switch ($operator) {
                default:
                case '=':
                case '==':  return $retrieved == $value;
                case '!=':
                case '<>':  return $retrieved != $value;
                case '<':   return $retrieved < $value;
                case '>':   return $retrieved > $value;
                case '<=':  return $retrieved <= $value;
                case '>=':  return $retrieved >= $value;
                case '===': return $retrieved === $value;
                case '!==': return $retrieved !== $value;
                case '<=>': return $retrieved <=> $value;
            }
        };
    }
}
