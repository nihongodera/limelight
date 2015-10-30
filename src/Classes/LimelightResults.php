<?php

namespace Limelight\Classes;

use Limelight\Exceptions\LimelightInvalidInputException;

class LimelightResults
{
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
     * Construct.
     *
     * @param string $text
     * @param array  $words
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
     * @return generator
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
     * Get the original, user inputed text.
     *
     * @return string
     */
    public function getOriginal()
    {
        return $this->text;
    }

    /**
     * Get all words combined as a string.
     *
     * @return string
     */
    public function getResultString()
    {
        $string = '';

        foreach ($this->words as $word) {
            $string .= $word->word()->get();
        }

        return $string;
    }

    /**
     * Get all lemmas combined as a string.
     * 
     * @return [type] [description]
     */
    public function getLemmaString()
    {
        $string = '';

        foreach ($this->words as $word) {
            $string .= $word->lemma()->get();
        }

        return $string;
    }

    /**
     * Get all words.
     *
     * @return $this
     */
    public function getAll()
    {
        return $this->words;
    }

    /**
     * Get next word.
     *
     * @return function
     */
    public function getNext()
    {
        $count = count($this->words);

        for ($i = 0; $i < $count; ++$i) {
            yield ($this->words[$i]);
        }
    }

    /**
     * Get single word by word.
     *
     * @param string $string
     *
     * @return Limelight\Classes\LimelightWord
     */
    public function getByWord($string)
    {
        foreach ($this->words as $word) {
            if ($word->word()->get() === $string) {
                return $word;
            }
        }

        throw new LimelightInvalidInputException("Word {$string} does not exist.");
    }

    /**
     * Get single word by index.
     *
     * @param int $position
     *
     * @return Limelight\Classes\LimelightWord
     */
    public function getByIndex($index)
    {
        $count = count($this->words);

        if ($count <= $index) {
            throw new LimelightInvalidInputException("Index {$index} does not exist. Results contain exactly {$count} item(s).");
        }

        return $this->words[$index];
    }

    /**
     * Get plugin data from object.
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
}
