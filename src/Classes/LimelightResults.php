<?php

declare(strict_types=1);

namespace Limelight\Classes;

use Limelight\Helpers\PluginHelper;
use Limelight\Helpers\Contracts\Jsonable;
use Limelight\Helpers\Contracts\Arrayable;
use Limelight\Helpers\Contracts\Convertable;
use Limelight\Exceptions\PluginNotFoundException;

class LimelightResults extends Collection implements Arrayable, Convertable, Jsonable
{
    use PluginHelper;

    /**
     * The original input.
     */
    protected string $text;

    /**
     * Array of words returned from parser.
     */
    protected array $words;

    /**
     * Results from plugins.
     */
    protected ?array $pluginData = [];

    public function __construct(string $text, array $words, ?array $pluginData)
    {
        $this->text = $text;
        $this->words = $words;
        $this->pluginData = $pluginData;
    }

    /**
     * Print JSON.
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Get values as string.
     */
    public function string(string $value, ?string $glue = null): string
    {
        $value = $this->makeSingular($value);

        $collection = $this->map(function ($item) use ($value, $glue) {
            return $this->buildString($item, $value, $glue);
        });

        return $this->cutFirst($collection, $glue);
    }

    /**
     * Get the original, user input text.
     */
    public function original(): string
    {
        return $this->text;
    }

    /**
     * Get all words.
     */
    public function words(): LimelightResults
    {
        return $this->pluck('word');
    }

    /**
     * Get all lemmas.
     */
    public function lemmas(): LimelightResults
    {
        return $this->pluck('lemma');
    }

    /**
     * Get all readings.
     */
    public function readings(): LimelightResults
    {
        return $this->pluck('reading');
    }

    /**
     * Get all pronunciations.
     */
    public function pronunciations(): LimelightResults
    {
        return $this->pluck('pronunciation');
    }

    /**
     * Get all partsOfSpeech.
     */
    public function partsOfSpeech(): LimelightResults
    {
        return $this->pluck('partOfSpeech');
    }

    /**
     * Get romaji if data exists.
     *
     * @throws PluginNotFoundException
     */
    public function romaji(): ?LimelightResults
    {
        return $this->getPluginData('romaji');
    }

    /**
     * Get furigana if data exists.
     *
     * @throws PluginNotFoundException
     */
    public function furigana(): ?LimelightResults
    {
        return $this->getPluginData('furigana');
    }

    /**
     * Convert items to hiragana.
     */
    public function toHiragana(): LimelightResults
    {
        return $this->convert('hiragana');
    }

    /**
     * Convert items to katakana.
     */
    public function toKatakana(): LimelightResults
    {
        return $this->convert('katakana');
    }

    /**
     * Get plugin data from object.
     */
    public function plugin(string $name)
    {
        return $this->getPluginData($name, 'self');
    }

    /**
     * Build string for word.
     */
    private function buildString(LimelightWord $item, string $value, ?string $glue): string
    {
        if ($this->shouldNotGlue($item, $value, $glue)) {
            return $item->$value;
        }

        return $glue.$item->$value;
    }

    /**
     * Return true if it should not prefix with glue.
     */
    private function shouldNotGlue(LimelightWord $item, string $value, ?string $glue): bool
    {
        if ($glue === null) {
            return true;
        }

        return $value !== 'partOfSpeech' &&
            $item->partOfSpeech === 'symbol' &&
            preg_match('/\\s/', $glue);
    }

    /**
     * Cut first chars if its is glue.
     */
    private function cutFirst(Collection $collection, ?string $glue): string
    {
        $string = implode('', $collection->all());

        if ($glue) {
            return implode($glue, array_filter(explode($glue, $string)));
        }

        return $string;
    }

    /**
     * Make value singular.
     */
    private function makeSingular(string $value): string
    {
        if (substr($value, -1) === 's') {
            return substr($value, 0, -1);
        }

        return $value;
    }
}
