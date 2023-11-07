<?php

declare(strict_types=1);

namespace Limelight\Classes;

use Limelight\Limelight;
use Limelight\Helpers\Converter;
use Limelight\Helpers\PluginHelper;
use Limelight\Helpers\JapaneseHelpers;
use Limelight\Helpers\Contracts\Jsonable;
use Limelight\Helpers\Contracts\Arrayable;
use Limelight\Helpers\Contracts\Convertable;

class LimelightWord implements Arrayable, Convertable, Jsonable
{
    use JapaneseHelpers;
    use PluginHelper;

    /**
     * Raw mecab data for word.
     */
    public array $rawMecab;

    /**
     * The word.
     */
    public string $word;

    /**
     * Dictionary entry for word.
     */
    public string $lemma;

    /**
     * Word reading.
     */
    public ?string $reading;

    /**
     * Word pronunciation.
     */
    public ?string $pronunciation;

    /**
     * Word part of speech.
     */
    public ?string $partOfSpeech;

    /**
     * Grammar for word.
     */
    public ?string $grammar;

    /**
     * True if word was successfully parsed.
     */
    public bool $parsed = false;

    /**
     * Results from plugins.
     */
    public array $pluginData = [];

    private Limelight $limelight;

    public function __construct(array $token, array $properties, Limelight $limelight)
    {
        $this->setProperties($token, $properties);

        $this->limelight = $limelight;

        $this->setMissingParameters();
    }

    /**
     * Call methods for plugin items.
     */
    public function __call(string $name, array $arguments): ?array
    {
        if (isset($this->pluginData[ucfirst($name)])) {
            return $this->pluginData[ucfirst($name)];
        }

        throw new \RuntimeException(sprintf('No method "%s" found in plugin data', $name));
    }

    /**
     * Get private properties.
     */
    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        if (isset($this->pluginData[ucfirst($name)])) {
            return $this->pluginData[ucfirst($name)];
        }

        throw new \RuntimeException(sprintf('No property "%s" found in object or plugin data', $name));
    }

    /**
     * Print JSON.
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $publicProperties = $this->getPublicProperties();

        $return = [];

        foreach ($publicProperties as $object) {
            $value = $object->name;

            $return[$value] = $this->$value;
        }

        return $return;
    }

    /**
     * Convert the object to its JSON representation.
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * Convert word properties to given format.
     */
    public function convert(string $format): self
    {
        $publicProperties = $this->getPublicProperties();

        foreach ($publicProperties as $object) {
            $value = $object->name;

            if (is_string($this->$value) || is_array($this->$value)) {
                $this->$value = Converter::convert($this->$value, $format);
            }
        }

        return $this;
    }

    /**
     * Get tokens for word.
     */
    public function rawMecab(): array
    {
        return $this->rawMecab;
    }

    /**
     * Get word.
     */
    public function get(): string
    {
        return $this->word();
    }

    /**
     * Get word.
     */
    public function word(): string
    {
        return $this->word;
    }

    /**
     * Get lemma (dictionary entry) for word.
     */
    public function lemma(): string
    {
        return $this->lemma;
    }

    /**
     * Get reading for word.
     */
    public function reading(): ?string
    {
        return $this->reading;
    }

    /**
     * Get pronunciation for word.
     */
    public function pronunciation(): ?string
    {
        return $this->pronunciation;
    }

    /**
     * Get part of speech for word.
     */
    public function partOfSpeech(): ?string
    {
        return $this->partOfSpeech;
    }

    /**
     * Get grammar for word, if any.
     */
    public function grammar(): ?string
    {
        return $this->grammar;
    }

    /**
     * Return true if word was successfully parsed.
     */
    public function parsed(): bool
    {
        return $this->parsed;
    }

    /**
     * Get plugin data by plugin name.
     */
    public function plugin(string $name)
    {
        return $this->getPluginData($name);
    }

    /**
     * Get romaji if set.
     */
    public function romaji(): ?string
    {
        return $this->getPluginData('romaji');
    }

    /**
     * Get furigana if set.
     */
    public function furigana(): ?string
    {
        return $this->getPluginData('furigana');
    }

    /**
     * Set $this->conversionFlag to hiragana.
     */
    public function toHiragana(): self
    {
        return $this->convert('hiragana');
    }

    /**
     * Set $this->conversionFlag to katakana.
     */
    public function toKatakana(): self
    {
        return $this->convert('katakana');
    }

    /**
     * Parse the lemma with Limelight.
     */
    public function parseLemma(): LimelightWord
    {
        return $this->limelight->parse($this->lemma)->first();
    }

    /**
     * Append value to end of property.
     *
     * @param string|array $value
     */
    public function appendTo(string $property, $value): void
    {
        if (is_array($this->$property)) {
            $this->$property[] = $value;
        } else {
            $this->$property .= $value;
        }
    }

    /**
     * Set the part of speech for the word.
     */
    public function setPartOfSpeech(?string $value): void
    {
        $this->partOfSpeech = $value;
    }

    /**
     * Set plugin data on object.
     */
    public function setPluginData(string $pluginName, $value): void
    {
        $this->pluginData[ucfirst($pluginName)] = $value;
    }

    /**
     * Get the objects public properties.
     */
    private function getPublicProperties(): array
    {
        return (
            new \ReflectionObject($this))->getProperties(
                \ReflectionProperty::IS_PUBLIC
            );
    }

    /**
     * Set properties on object.
     */
    private function setProperties(array $token, array $properties): void
    {
        $this->rawMecab = [$token];

        $this->word = $token['literal'];

        $this->lemma = $token['lemma'];

        $this->reading = $token['reading'] ?? null;

        $this->pronunciation = $token['pronunciation'] ?? null;

        $this->partOfSpeech = $properties['partOfSpeech'];

        $this->grammar = $properties['grammar'];

        if (!is_null($this->reading)) {
            $this->parsed = true;
        }
    }

    /**
     * Set missing parameters for kana words that were not parsed.
     */
    private function setMissingParameters(): void
    {
        if (!isset($this->reading) && !$this->hasKanji($this->word)) {
            $this->reading = $this->word;

            $this->pronunciation = $this->word;

            $results = $this->limelight->noParse($this->word(), ['Romaji'], true);

            $this->setPluginData('Romaji', $results->string('romaji'));
        }
    }
}
