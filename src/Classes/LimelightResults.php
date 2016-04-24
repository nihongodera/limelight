<?php

namespace Limelight\Classes;

use Limelight\Helpers\ResultsHelpers;
use Limelight\Helpers\Contracts\Jsonable;
use Limelight\Helpers\Contracts\Arrayable;

class LimelightResults extends Collection implements Arrayable, Jsonable
{
    use ResultsHelpers;

    /**
     * The original input.
     *
     * @var string
     */
    protected $text;

    /**
     * Array of words returned from parser.
     *
     * @var array
     */
    protected $words;

    /**
     * Results from plugins.
     *
     * @var array
     */
    protected $pluginData = [];

    /**
     * Construct.
     *
     * @param string     $text
     * @param array      $words
     * @param array/null $pluginData
     */
    public function __construct($text, array $words, $pluginData)
    {
        $this->text = $text;
        $this->words = $words;
        $this->pluginData = $pluginData;
    }

    /**
     * Print result info.
     *
     * @return string
     */
    public function __toString()
    {
        $string = '';

        foreach ($this->words as $word) {
            $string .= $word."\n";
        }

        return $string;
    }

    /**
     * Get values as string.
     *
     * @param string $value [value]
     * @param string $glue  [word divider]
     *
     * @return string
     */
    public function string($value, $glue = null)
    {
        $string = $this->map(function ($item, $key) use ($value, $glue) {
            if ($item->partOfSpeech === 'symbol' && preg_match('/\\s/', $glue)) {
                return $item->$value;
            }

            return $glue.$item->$value;
        });

        return $this->cutFirst(implode('', $string->all()), $glue);
    }

    /**
     * Get the original, user inputed text.
     *
     * @return string
     */
    public function original()
    {
        return $this->text;
    }

    /**
     * Get all words combined as a string.
     *
     * @param bool   $spaces  [put divider between words]
     * @param string $divider
     *
     * @return string
     */
    public function words($spaces = false, $divider = ' ')
    {
        return $this->pluck('word');
    }

    /**
     * Get all lemmas combined as a string.
     *
     * @param bool   $spaces  [put divider between words]
     * @param string $divider
     *
     * @return string
     */
    public function lemmas($spaces = false, $divider = ' ')
    {
        return $this->pluck('lemma');
    }

    /**
     * Get all readings combined as a string.
     *
     * @param bool   $spaces  [put divider between words]
     * @param string $divider
     *
     * @return string
     */
    public function readings($spaces = false, $divider = ' ')
    {
        return $this->pluck('reading');
    }

    /**
     * Get all pronunciations combined as a string.
     *
     * @param bool   $spaces  [put divider between words]
     * @param string $divider
     *
     * @return string
     */
    public function pronunciations($spaces = false, $divider = ' ')
    {
        return $this->pluck('pronunciation');
    }

    /**
     * Get all partsOfSpeech combined as a space sseerated string.
     *
     * @param bool   $spaces  [put divider between words]
     * @param string $divider
     *
     * @return string
     */
    public function partsOfSpeech($spaces = true, $divider = ' ')
    {
        return $this->pluck('partOfSpeech');
    }

    /**
     * Set $this->conversionFlag to hiragana.
     *
     * @return $this
     */
    public function toHiragana()
    {
        // $this->conversionFlag = 'hiragana';

        $first = $this->first();

        if ($first instanceof LimelightWord) {
            var_dump(555);
        }

        return $this;
    }

    /**
     * Set $this->conversionFlag to katakana.
     *
     * @return $this
     */
    public function toKatakana()
    {
        $this->conversionFlag = 'katakana';

        return $this;
    }

    /**
     * Set $this->conversionFlag to romaji.
     *
     * @return $this
     */
    public function toRomaji()
    {
        $this->checkPlugin('romaji');

        $this->conversionFlag = 'romaji';

        return $this;
    }

    /**
     * Set $this->conversionFlag to furigana.
     *
     * @return $this
     */
    public function toFurigana()
    {
        $this->checkPlugin('furigana');

        $this->conversionFlag = 'furigana';

        return $this;
    }

    /**
     * Cut last char if its is divider.
     *
     * @param string $string
     * @param string $divider
     *
     * @return string
     */
    private function cutFirst($string, $divider)
    {
        if (mb_substr($string, 0, 1) === $divider) {
            return mb_substr($string, 1);
        }

        return $string;
    }
}
