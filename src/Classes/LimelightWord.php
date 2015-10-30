<?php

namespace Limelight\Classes;

class LimelightWord
{
    /**
     * Items returned by get().
     *
     * @var mixed
     */
    private $returnItem;

    /**
     * Raw mecab data for word.
     *
     * @var array
     */
    private $rawMecab;

    /**
     * The word.
     *
     * @var string
     */
    private $word;

    /**
     * Dictionary entry for word.
     *
     * @var string
     */
    private $lemma;

    /**
     * Word reading.
     *
     * @var string
     */
    private $reading;

    /**
     * Word pronunciation.
     *
     * @var string
     */
    private $pronunciation;

    /**
     * Word part of speech.
     *
     * @var string
     */
    private $partOfSpeech;

    /**
     * Grammar for word.
     *
     * @var string
     */
    private $grammar;

    /**
     * Results from plugins.
     *
     * @var array
     */
    private $pluginData = [];

    /**
     * Construct.
     *
     * @param array $token
     * @param array $properties
     */
    public function __construct($token, $properties)
    {
        $this->rawMecab = [$token];
        $this->word = $token['literal'];
        $this->lemma = $token['lemma'];
        $this->reading = (isset($token['reading']) ?  $token['reading'] : null);
        $this->pronunciation = (isset($token['pronunciation']) ?  $token['pronunciation'] : null);
        $this->partOfSpeech = $properties['partOfSpeech'];
        $this->grammar = $properties['grammar'];
    }

    /**
     * Get private properties.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }

    /**
     * Print word info.
     *
     * @return string
     */
    public function __toString()
    {
        return "Word:\t\t{$this->word}\n".
               "Part of Speech:\t{$this->partOfSpeech}\n".
               "Lemma:\t\t{$this->lemma}\n".
               "Reading:\t{$this->reading}\n".
               "Pronunciation:\t{$this->pronunciation}\n";
    }

    /**
     * Return $this->returnItem.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->returnItem;
    }

    /**
     * Get tokens for word.
     *
     * @return $this
     */
    public function rawMecab()
    {
        $this->returnItem = $this->rawMecab;

        return $this;
    }

    /**
     * Get word.
     *
     * @return $this
     */
    public function word()
    {
        $this->returnItem = $this->word;

        return $this;
    }

    /**
     * Get lemma (dictionary entry) for word.
     *
     * @return $this
     */
    public function lemma()
    {
        $this->returnItem = $this->lemma;

        return $this;
    }

    /**
     * Get reading for word.
     *
     * @return $this
     */
    public function reading()
    {
        $this->returnItem = $this->reading;

        return $this;
    }

    /**
     * Get pronunciation for word.
     *
     * @return $this
     */
    public function pronunciation()
    {
        $this->returnItem = $this->pronunciation;

        return $this;
    }

    /**
     * Get part of speech for word.
     *
     * @return $this
     */
    public function partOfSpeech()
    {
        $this->returnItem = $this->partOfSpeech;

        return $this;
    }

    /**
     * Get grammar for word, if any.
     *
     * @return $this
     */
    public function grammar()
    {
        $this->returnItem = $this->grammar;

        return $this;
    }

    /**
     * Get plugin data from word.
     *
     * @param string $pluginName [The name of the plugin]
     *
     * @return mixed
     */
    public function plugin($pluginName)
    {
        if (isset($this->pluginData[$pluginName])) {
            return $this->pluginData[$pluginName];
        }

        return;
    }

    /**
     * Convert $this->returnItem to hiragana if possible.
     *
     * @return $this
     */
    public function toHiragana()
    {
        if (gettype($this->returnItem) === 'string') {
            $this->returnItem = mb_convert_kana($this->returnItem, 'c');
        }

        return $this;
    }

    /**
     * Convert $this->returnItem to katakana if possible.
     *
     * @return $this
     */
    public function toKatakana()
    {
        if (gettype($this->returnItem) === 'string') {
            $this->returnItem = mb_convert_kana($this->returnItem, 'C');
        }

        return $this;
    }

    /**
     * Append value to end of property.
     *
     * @param string $property
     * @param mixed  $value
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
}
