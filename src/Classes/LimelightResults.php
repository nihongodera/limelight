<?php

namespace Limelight\Classes;

use Limelight\Helpers\ResultsHelpers;
use Limelight\Exceptions\InvalidInputException;

class LimelightResults
{
    use ResultsHelpers;

    /**
     * The original input.
     *
     * @var string
     */
    private $text;

    /**
     * Array of words returned from parser.
     *
     * @var array
     */
    private $words;

    /**
     * Results from plugins.
     *
     * @var array
     */
    private $pluginData = [];

    /**
     * Flag for calling Converter on LimelightWord.
     *
     * @var null/string
     */
    private $conversionFlag = null;

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
     * Call generator if invoked as function.
     *
     * @return function
     */
    public function __invoke()
    {
        return $this->next();
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
     * Get all words.
     *
     * @return $this
     */
    public function all()
    {
        return $this->words;
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
    public function get($spaces = false, $divider = ' ')
    {
        return $this->words($spaces, $divider);
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
        return $this->makePropertyString('word', $spaces, $divider);
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
        return $this->makePropertyString('lemma', $spaces, $divider);
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
        return $this->makePropertyString('reading', $spaces, $divider);
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
        return $this->makePropertyString('pronunciation', $spaces, $divider);
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
        return $this->makePropertyString('partOfSpeech', $spaces, $divider);
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
     * Get next word.
     *
     * @return function
     */
    public function next()
    {
        $count = count($this->words);

        for ($i = 0; $i < $count; ++$i) {
            yield $this->words[$i];
        }
    }

    /**
     * Get single word, by word.
     *
     * @param string $string
     *
     * @return Limelight\Classes\LimelightWord/InvalidInputException
     */
    public function findWord($string)
    {
        foreach ($this->words as $word) {
            if ($word->word() === $string) {
                return $word;
            }
        }

        throw new InvalidInputException("Word {$string} does not exist.");
    }

    /**
     * Get single word by index.
     *
     * @param int $index
     *
     * @return Limelight\Classes\LimelightWord/InvalidInputException
     */
    public function findIndex($index)
    {
        $count = count($this->words);

        if ($count <= $index) {
            throw new InvalidInputException("Index {$index} does not exist. Results contain exactly {$count} item(s).");
        }

        return $this->words[$index];
    }

    /**
     * Loop through words to make string of properties.
     *
     * @param string $property
     * @param bool   $space    [should results be sparated by spaces?]
     *
     * @return string
     */
    private function makePropertyString($property, $space = false, $divider = ' ')
    {
        if ($this->isNonLemmaPlugin($property)) {
            $string = $this->plugin(ucfirst($this->conversionFlag));

            if ($divider !== ' ') {
                $string = mb_ereg_replace(' ', $divider, $string);
            }

            return $string;
        }

        $string = '';

        foreach ($this->words as $word) {
            $word->setConversionFlag($this->conversionFlag);

            if ($this->shouldTrim($word, $string, $property)) {
                $string = substr($string, 0, -1);
            }

            $string .= $word->$property().($space === true || $this->conversionFlag === 'romaji' ? $divider : '');
        }

        $this->conversionFlag = null;

        return $this->cutLast($string, $divider);
    }

    /**
     * Property is not lemma and conversionFlag is a plugin.
     *
     * @param string $property
     *
     * @return bool
     */
    private function isNonLemmaPlugin($property)
    {
        return ($this->conversionFlag === 'furigana' || $this->conversionFlag === 'romaji') && $property !== 'lemma';
    }

    /**
     * Results string should have last space trimmed.
     *
     * @param LimelightWord $word
     * @param string        $string
     *
     * @return bool
     */
    private function shouldTrim($word, $string, $property)
    {
        return $word->partOfSpeech === 'symbol' && substr($string, -1) === ' ' && $property !== 'partOfSpeech';
    }

    /**
     * Cut last char if its is divider.
     * 
     * @param string $string
     * @param string $divider
     * 
     * @return string
     */
    private function cutLast($string, $divider)
    {
        if (mb_substr($string, -1) === $divider) {
            return mb_substr($string, 0, -1);
        }

        return $string;
    }
}
