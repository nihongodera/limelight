<?php

declare(strict_types=1);

namespace Limelight\Helpers;

class Converter
{
    /**
     * Convert item to given format.
     *
     * @param string|array $items
     *
     * @return string|array
     */
    public static function convert($items, string $format)
    {
        if (!is_array($items)) {
            return static::$format($items);
        }

        return array_map(static function ($value) use ($format) {
            if (is_array($value)) {
                return static::convert($value, $format);
            }

            return static::$format($value);
        }, $items);
    }

    /**
     * Convert to katakana.
     */
    private static function katakana(string $value): string
    {
        return static::convertKana($value, 'C');
    }

    /**
     * Convert to hiragana.
     */
    private static function hiragana(string $value): string
    {
        return static::convertKana($value, 'c');
    }

    /**
     * Handle kana conversions.
     */
    private static function convertKana(string $value, string $flag): string
    {
        return mb_convert_kana($value, $flag);
    }
}
