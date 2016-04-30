<?php

namespace Limelight\Helpers;

class Converter
{
    /**
     * Convert item to given format.
     *
     * @param string|array $items
     * @param string       $format
     *
     * @return string|array
     */
    public static function convert($items, $format)
    {
        if (!is_array($items)) {
            return static::$format($items);
        }

        return array_map(function ($value) use ($format) {
            if (is_array($value)) {
                return static::convert($value, $format);
            }

            return static::$format($value);
        }, $items);
    }

    /**
     * Convert to katakana.
     *
     * @param string $property
     * @param array  $wordData
     *
     * @return string
     */
    private static function katakana($value)
    {
        return static::convertKana($value, 'C');
    }

    /**
     * Convert to hiragana.
     *
     * @param string $property
     * @param array  $wordData
     *
     * @return string
     */
    private static function hiragana($value)
    {
        return static::convertKana($value, 'c');
    }

    /**
     * Handle kana conversions.
     *
     * @param string $property
     * @param array  $wordData
     * @param string $flag
     *
     * @return string
     */
    private static function convertKana($value, $flag)
    {
        return mb_convert_kana($value, $flag);
    }
}
