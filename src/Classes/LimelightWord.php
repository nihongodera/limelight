<?php

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
    use PluginHelper, JapaneseHelpers;

    /**
     * Raw mecab data for word.
     *
     * @var array
     */
    public $rawMecab;

    /**
     * The word.
     *
     * @var string
     */
    public $word;

    /**
     * Dictionary entry for word.
     *
     * @var string
     */
    public $lemma;

    /**
     * Word reading.
     *
     * @var string
     */
    public $reading;

    /**
     * Word pronunciation.
     *
     * @var string
     */
    public $pronunciation;

    /**
     * Word part of speech.
     *
     * @var string
     */
    public $partOfSpeech;

    /**
     * Grammar for word.
     *
     * @var string
     */
    public $grammar;

    /**
     * True if word was successfully parsed.
     *
     * @var bool
     */
    public $parsed = false;

    /**
     * Results from plugins.
     *
     * @var array
     */
    public $pluginData = [];

    /**
     * @var Limelight\Limelight
     */
    private $limelight;

    /**
     * Construct.
     *
     * @param array $token
     * @param array $properties
     */
    public function __construct($token, $properties, Limelight $limelight)
    {
        $this->setProperties($token, $properties);

        $this->limelight = $limelight;

        $this->setMissingParameters();
    }

    /**
     * Call methods for plugin items.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return array
     */
    public function __call($name, $arguments)
    {
        if (isset($this->pluginData[ucfirst($name)])) {
            return $this->pluginData[ucfirst($name)];
        }
    }

    /**
     * Get private properties.
     *
     * @param string $name
     *
     * @return string|array
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } elseif (isset($this->pluginData[ucfirst($name)])) {
            return $this->pluginData[ucfirst($name)];
        }
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
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
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
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }

    /**
     * Convert word properties to given format.
     *
     * @param string $format
     *
     * @return static
     */
    public function convert($format)
    {
        $publicProperties = $this->getPublicProperties();

        foreach ($publicProperties as $object) {
            $value = $object->name;

            $this->$value = Converter::convert($this->$value, $format);
        }

        return $this;
    }

    /**
     * Get tokens for word.
     *
     * @return array
     */
    public function rawMecab()
    {
        return $this->rawMecab;
    }

    /**
     * Get word.
     *
     * @return string
     */
    public function get()
    {
        return $this->word();
    }

    /**
     * Get word.
     *
     * @return string
     */
    public function word()
    {
        return $this->word;
    }

    /**
     * Get lemma (dictionary entry) for word.
     *
     * @return string
     */
    public function lemma()
    {
        return $this->lemma;
    }

    /**
     * Get reading for word.
     *
     * @return string
     */
    public function reading()
    {
        return $this->reading;
    }

    /**
     * Get pronunciation for word.
     *
     * @return string
     */
    public function pronunciation()
    {
        return $this->pronunciation;
    }

    /**
     * Get part of speech for word.
     *
     * @return string
     */
    public function partOfSpeech()
    {
        return $this->partOfSpeech;
    }

    /**
     * Get grammar for word, if any.
     *
     * @return string
     */
    public function grammar()
    {
        return $this->grammar;
    }

    /**
     * Return true if word was successfully parsed.
     *
     * @return bool
     */
    public function parsed()
    {
        return $this->parsed;
    }

    /**
     * Get plugin data by plugin name.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function plugin($name)
    {
        return $this->getPluginData($name);
    }

    /**
     * Get romaji if set.
     *
     * @return string
     */
    public function romaji()
    {
        return $this->getPluginData('romaji');
    }

    /**
     * Get furigana if set.
     *
     * @return string
     */
    public function furigana()
    {
        return $this->getPluginData('furigana');
    }

    /**
     * Set $this->conversionFlag to hiragana.
     *
     * @return $this
     */
    public function toHiragana()
    {
        return $this->convert('hiragana');
    }

    /**
     * Set $this->conversionFlag to katakana.
     *
     * @return $this
     */
    public function toKatakana()
    {
        return $this->convert('katakana');
    }

    /**
     * Parse the lemma with Limelight.
     *
     * @return LimelightWord
     */
    public function parseLemma()
    {
        return $this->limelight->parse($this->lemma)->first();
    }

    /**
     * Append value to end of property.
     *
     * @param string       $property
     * @param string|array $value
     */
    public function appendTo($property, $value)
    {
        if (is_array($this->$property)) {
            array_push($this->$property, $value);
        } else {
            $this->$property .= $value;
        }
    }

    /**
     * Set the part of speech for the word.
     *
     * @param string $value
     */
    public function setPartOfSpeech($value)
    {
        $this->partOfSpeech = $value;
    }

    /**
     * Set plugin data on object.
     *
     * @param string $pluginName [name of the plugin]
     * @param mixed  $value      [the value to store]
     */
    public function setPluginData($pluginName, $value)
    {
        $this->pluginData[ucfirst($pluginName)] = $value;
    }

    /**
     * Get the objects public properties.
     *
     * @return array
     */
    private function getPublicProperties()
    {
        return (
            new \ReflectionObject($this))->getProperties(\ReflectionProperty::IS_PUBLIC
        );
    }

    /**
     * Set properties on object.
     *
     * @param array $token
     * @param array $properties
     */
    private function setProperties($token, $properties)
    {
        $this->rawMecab = [$token];

        $this->word = $token['literal'];

        $this->lemma = $token['lemma'];

        $this->reading = (isset($token['reading']) ? $token['reading'] : null);

        $this->pronunciation = (isset($token['pronunciation']) ? $token['pronunciation'] : null);

        $this->partOfSpeech = $properties['partOfSpeech'];

        $this->grammar = $properties['grammar'];

        if (!is_null($this->reading)) {
            $this->parsed = true;
        }
    }

    /**
     * Set missing parameters for kana words that were not parsed.
     */
    private function setMissingParameters()
    {
        if (!$this->hasKanji($this->word) && !isset($this->reading)) {
            $this->reading = $this->word;

            $this->pronunciation = $this->word;

            $results = $this->limelight->noParse($this->word(), ['Romaji'], true);

            $this->setPluginData('Romaji', $results->string('romaji'));
        }
    }
}
