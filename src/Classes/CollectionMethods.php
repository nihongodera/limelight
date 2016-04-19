<?php

namespace Limelight\Classes;

trait CollectionMethods
{
    /**
     * Collection methods taken from Laravel Collection.
     * https://github.com/illuminate/support/blob/master/Collection.php
     */
    
    /**
     * Run a map over each of the items.
     *
     * @param  callable  $callback
     * @return static
     */
    public function map(callable $callback)
    {
        $keys = array_keys($this->words);

        $items = array_map($callback, $this->words, $keys);

        return array_combine($keys, $items);
    }

    /**
     * Run a filter over each of the items.
     *
     * @param  callable|null  $callback
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
            return $return;
        }
        return array_filter($this->words);
    }
}
