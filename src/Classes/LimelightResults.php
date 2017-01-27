<?php

namespace Limelight\Classes;

use Limelight\Helpers\Contracts\Arrayable;
use Limelight\Helpers\Contracts\Convertable;
use Limelight\Helpers\Contracts\Jsonable;
use Limelight\Helpers\PluginHelper;

class LimelightResults extends Collection implements Arrayable, Convertable, Jsonable
{
    use PluginHelper;

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
     * Print JSON.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
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
        $value = $this->makeSingular($value);

        $string = $this->map(function ($item, $key) use ($value, $glue) {
            return $this->buildString($item, $value, $glue);
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
     * Get all words.
     *
     * @return static
     */
    public function words()
    {
        return $this->pluck('word');
    }

    /**
     * Get all lemmas.
     *
     * @return static
     */
    public function lemmas()
    {
        return $this->pluck('lemma');
    }

    /**
     * Get all readings.
     *
     * @return static
     */
    public function readings()
    {
        return $this->pluck('reading');
    }

    /**
     * Get all pronunciations.
     *
     * @return static
     */
    public function pronunciations()
    {
        return $this->pluck('pronunciation');
    }

    /**
     * Get all partsOfSpeech.
     *
     * @return static
     */
    public function partsOfSpeech()
    {
        return $this->pluck('partOfSpeech');
    }

    /**
     * Get romaji if data exists.
     *
     * @throws PluginNotFoundException
     *
     * @return static
     */
    public function romaji()
    {
        return $this->getPluginData('romaji');
    }

    /**
     * Get furigana if data exists.
     *
     * @throws PluginNotFoundException
     *
     * @return static
     */
    public function furigana()
    {
        return $this->getPluginData('furigana');
    }

    /**
     * Convert items to hiragana.
     *
     * @return $this
     */
    public function toHiragana()
    {
        return $this->convert('hiragana');
    }

    /**
     * Convert items to katakana.
     *
     * @return $this
     */
    public function toKatakana()
    {
        return $this->convert('katakana');
    }

    /**
     * Get plugin data from object.
     *
     * @param string $name [The name of the plugin]
     *
     * @return mixed|bool
     */
    public function plugin($name)
    {
        return $this->getPluginData($name, 'self');
    }

    /**
     * Build string for word.
     *
     * @param LimelightWord $item
     * @param string        $value
     * @param string|null   $glue
     *
     * @return string
     */
    private function buildString($item, $value, $glue)
    {
        if ($value !== 'partOfSpeech' && $item->partOfSpeech === 'symbol' && preg_match('/\\s/', $glue)) {
            return $item->$value;
        }

        return $glue.$item->$value;
    }

    /**
     * Cut first char if its is divider.
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

    /**
     * Make value singular.
     *
     * @param string $value
     *
     * @return string
     */
    private function makeSingular($value)
    {
        if (substr($value, -1) === 's') {
            return substr($value, 0, -1);
        }

        return $value;
    }
}
