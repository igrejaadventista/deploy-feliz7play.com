<?php

declare(strict_types=1);

namespace Metricool\Support\Helpers;

use ArrayIterator;
use Closure;
use IteratorAggregate;

final class Collection implements IteratorAggregate
{
    /**
     * The items contained in the collection.
     */
    protected array $items = [];

    /**
     * Create a new collection.
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Get all the items in the collection.
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Get first item from the collection
     * @return mixed|null
     */
    public function first()
    {
        return reset($this->items) ?: null;
    }

    public function take(int $count): Collection
    {
        return new Collection(array_slice($this->items, 0, $count));
    }

    /**
     * Push one or more items to the end of the collection
     * @param mixed ...$values
     */
    public function push(...$values): self
    {
        foreach ($values as $value) {
            $this->items[] = $value;
        }

        return $this;
    }

    /**
     * Sort the collection using the given callback.
     * @param callable|string|null $callback
     */
    public function sortBy($callback = null, bool $descending = false, bool $preserveKeys = false, int $options = SORT_REGULAR): self
    {
        $results = [];

        $callback = $this->valueRetriever($callback);

        // First we will loop through the items and get the comparator from a callback
        // function which we were given. Then, we will sort the returned values and
        // grab all the corresponding values for the sorted keys from this array.
        foreach ($this->items as $key => $value) {
            $results[$key] = $callback($value, $key);
        }

        $descending ? arsort($results, $options)
            : asort($results, $options);

        // Once we have sorted all of the keys in the array, we will loop through them
        // and grab the corresponding model so we can set the underlying items list
        // to the sorted version. Then we'll just return the collection instance.
        foreach (array_keys($results) as $key) {
            $results[$key] = $this->items[$key];
        }

        if (!$preserveKeys) {
            $results = array_values($results);
        }

        return new Collection($results);
    }

    /**
     * Sort the collection in ascending order.
     * @uses sortBy
     * @param callable|string|null $callback
     */
    public function sortByAsc($callback = null, bool $preserveKeys = false, int $options = SORT_REGULAR): self
    {
        return $this->sortBy($callback, false, $preserveKeys, $options);
    }

    /**
     * Sort the collection in descending order.
     * @uses sortBy
     * @param callable|string|null $callback
     */
    public function sortByDesc($callback = null, bool $preserveKeys = false, int $options = SORT_REGULAR): self
    {
        return $this->sortBy($callback, true, $preserveKeys, $options);
    }

    /**
     * Run a filter over each of the items.
     */
    public function filter(?callable $callback = null): self
    {
        if ($callback) {
            return new Collection(array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH));
        }

        return new Collection(array_filter($this->items));
    }

    /**
     * Return all keys of the collection items.
     */
    public function keys(): array
    {
        return array_keys($this->items);
    }

    /**
     * Get the sum of the given values.
     * @param callable|string|null $callback
     * @return float|int
     */
    public function sum($callback = null)
    {
        $callback = is_null($callback)
            ? $this->closure()
            : $this->valueRetriever($callback);

        return $this->reduce(function ($result, $item) use ($callback) {
            return $result + $callback($item);
        }, 0);
    }

    /**
     * Reduce the collection to a single value.
     * @param mixed $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        $result = $initial;

        foreach ($this as $key => $value) {
            $result = $callback($result, $value, $key);
        }

        return $result;
    }

    /**
     * Pluck an array of values from an array.
     * @param mixed $value
     * @param mixed|null $key
     */
    public function pluck($value, $key = null): array
    {
        $results = [];

        foreach ($this->items as $item) {
            $itemValue = $this->get($item, $value);

            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = $this->get($item, $key);
                $results[$itemKey] = $itemValue;
            }
        }

        return $results;
    }

    /**
     * Filter items by the given key value pair. Allows shorthands for:
     * $collection->where('property', 'value'); for a loose comparison check
     * and
     * $collection->where('property'); for a loose boolean check
     * @param mixed $value
     */
    public function where(string $key, ?string $operator = null, $value = null): self
    {
        return $this->filter($this->operatorForWhere(...func_get_args()));
    }

    /**
     * Filter items by the given key/value pairs.
     */
    public function whereIn(string $key, array $values): self
    {
        return $this->filter(function ($item) use ($key, $values) {
            return in_array($this->get($item, $key), $values);
        });
    }

    /**
     * Run a map over each of the items.
     */
    public function map(callable $callback): self
    {
        $keys = array_keys($this->items);

        $items = array_map($callback, $this->items, $keys);

        return new Collection(array_combine($keys, $items));
    }

    /**
     * Count the number of items in the collection.
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Get the collection of items as a plain array.
     */
    public function toArray(): array
    {
        return $this->map(function ($value) {
            return is_object($value) && method_exists($value, 'toArray') ? $value->toArray() : $value;
        })->all();
    }

    /**
     * Get a value retrieving callback.
     * @param callable|string|null $value
     */
    protected function valueRetriever($value): callable
    {
        return function ($item) use ($value) {
            return $this->get($item, $value);
        };
    }

    /**
     * Return the default value of the given value.
     * @param mixed $value
     * @param mixed ...$args
     * @return mixed
     */
    protected function value($value, ...$args)
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }

    /**
     * Get an item from an array or object using "dot" notation.
     * @param mixed $target
     * @param string|array|int|null $key
     * @param mixed $default
     * @return mixed
     */
    protected function get($target, $key = null, $default = null)
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

            if (is_array($target) && array_key_exists($segment, $target)) {
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
     * Make a function that returns what's passed to it.
     */
    protected function closure(): Closure
    {
        return function ($value) {
            return $value;
        };
    }

    /**
     * Get an iterator for the items.
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Get an operator checker callback.
     * @param mixed $value
     */
    protected function operatorForWhere(string $key, ?string $operator = null, $value = null): Closure
    {
        // Allow shorthands
        if (func_num_args() === 1) {
            $value = true;
            $operator = '=';
        }
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        return function ($item) use ($key, $operator, $value) {
            $retrieved = $this->get($item, $key);

            switch ($operator) {
                default:
                case '=':
                case '==':
                    return $retrieved == $value;
                case '!=':
                case '<>':
                    return $retrieved != $value;
                case '<':
                    return $retrieved < $value;
                case '>':
                    return $retrieved > $value;
                case '<=':
                    return $retrieved <= $value;
                case '>=':
                    return $retrieved >= $value;
                case '===':
                    return $retrieved === $value;
                case '!==':
                    return $retrieved !== $value;
            }
        };
    }
}
