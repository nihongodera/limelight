<?php

namespace Limelight\Classes;

use Limelight\Helpers\Converter;
use Limelight\Helpers\ResultsHelpers;

class LimelightWord
{
    use ResultsHelpers;

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
     * Converter for hiragana/katakana/romanji/furigana.
     *
     * @var Limelight\Helpers\Converter
     */
    private $converter;

    /**
     * Flag for calling Converter.
     *
     * @var null/string
     */
    private $conversionFlag = null;

    /**
     * Construct.
     *
     * @param array     $token
     * @param array     $properties
     * @param Converter $converter
     */
    public function __construct($token, $properties, Converter $converter)
    {
        $this->setProperties($token, $properties);

        $this->converter = $converter;
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
     * @return string/array
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
    public function word()
    {
        if (!is_null($this->conversionFlag)) {
            return $this->callConverter('word');
        }

        return $this->word;
    }

    /**
     * Get lemma (dictionary entry) for word.
     *
     * @return string
     */
    public function lemma()
    {
        if (!is_null($this->conversionFlag)) {
            return $this->callConverter('lemma');
        }

        return $this->lemma;
    }

    /**
     * Get reading for word.
     *
     * @return string
     */
    public function reading()
    {
        if (!is_null($this->conversionFlag)) {
            return $this->callConverter('reading');
        }

        return $this->reading;
    }

    /**
     * Get pronunciation for word.
     *
     * @return string
     */
    public function pronunciation()
    {
        if (!is_null($this->conversionFlag)) {
            return $this->callConverter('pronunciation');
        }

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
     * Set $this->conversionFlag to hiragana.
     *
     * @return $this
     */
    public function toHiragana()
    {
        $this->conversionFlag = 'hiragana';

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
     * Set $this->conversionFlag to romanji.
     *
     * @return $this
     */
    public function toRomanji()
    {
        $this->checkPlugin('romanji');

        $this->conversionFlag = 'romanji';

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
     * Append value to end of property.
     *
     * @param string       $property
     * @param string/array $value
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
     * Set the conversionFlag.
     *
     * @param string $flag
     */
    public function setConversionFlag($flag)
    {
        $this->conversionFlag = $flag;
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

        $this->reading = (isset($token['reading']) ?  $token['reading'] : null);

        $this->pronunciation = (isset($token['pronunciation']) ?  $token['pronunciation'] : null);

        $this->partOfSpeech = $properties['partOfSpeech'];

        $this->grammar = $properties['grammar'];
    }

    /**
     * Call $this->converter.
     *
     * @param string $property [Property to convert]
     *
     * @return string
     */
    private function callConverter($property)
    {
        $dto = $this->getAllData();

        $convertedString = $this->converter->convert($dto, $property, $this->conversionFlag);

        $this->conversionFlag = null;

        return $convertedString;
    }

    /**
     * Build dto for conversion.
     *
     * @return array
     */
    private function getAllData()
    {
        return [
            'word' => $this->word,
            'lemma' => $this->lemma,
            'reading' => $this->reading,
            'pronunciation' => $this->pronunciation,
            'furigana' => (isset($this->pluginData['Furigana']) ? $this->pluginData['Furigana'] : null),
            'romanji' => (isset($this->pluginData['Romanji']) ? $this->pluginData['Romanji'] : null),
        ];
    }
}
