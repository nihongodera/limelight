<?php

namespace Limelight\Helpers;

use Limelight\Limelight;

class Converter
{
    use JapaneseHelpers;

    /**
     * Limelight instance.
     * 
     * @var Limelight\Limelight
     */
    private $limelight;

    /**
     * Construct.
     *
     * @param Limelight $limelight
     */
    public function __construct(Limelight $limelight)
    {
        $this->limelight = $limelight;
    }

    /**
     * Handle conversion.
     *
     * @param array  $wordData       [Array of word data from LimelightWord]
     * @param string $property       [The property to convert]
     * @param string $conversionType [The desired conversion]
     *
     * @return string
     */
    public function convert(array $wordData, $property, $conversionType)
    {
        return $this->$conversionType($property, $wordData);
    }

    /**
     * Convert to katakana.
     *
     * @param string $property
     * @param array  $wordData
     *
     * @return string
     */
    private function katakana($property, array $wordData)
    {
        return $this->convertKana($property, $wordData, 'C');
    }

    /**
     * Convert to hiragana.
     *
     * @param string $property
     * @param array  $wordData
     *
     * @return string
     */
    private function hiragana($property, array $wordData)
    {
        return $this->convertKana($property, $wordData, 'c');
    }

    /**
     * Convert to furigana.
     *
     * @param string $property
     * @param array  $wordData
     *
     * @return string
     */
    private function furigana($property, array $wordData)
    {
        return $this->convertPlugin($property, $wordData, 'furigana');
    }

    /**
     * Convert to romaji.
     *
     * @param string $property
     * @param array  $wordData
     *
     * @return string
     */
    private function romaji($property, array $wordData)
    {
        return $this->convertPlugin($property, $wordData, 'romaji');
    }

    /**
     * Handle kana conversions.
     *
     * @param string $property
     * @param array  $wordData
     * @param string $flag
     *
     * @return string
     */
    private function convertKana($property, array $wordData, $flag)
    {
        if ($this->mustParseLemma($property, $wordData)) {
            $lemma = $this->parseLemma($wordData['lemma'])->reading();

            return mb_convert_kana($lemma, $flag);
        } elseif (($property === 'word' && $this->hasKanji($wordData['word'])) || $property === 'lemma') {
            $property = 'reading';
        }

        return mb_convert_kana($wordData[$property], $flag);
    }

    /**
     * Handle plugin conversions.
     *
     * @param string $property
     * @param array  $wordData
     * @param string $flag
     *
     * @return string
     */
    private function convertPlugin($property, array $wordData, $plugin)
    {
        if ($this->mustParseLemma($property, $wordData)) {
            $lemma = $this->parseLemma($wordData['lemma']);

            $method = 'to'.ucfirst($plugin);

            return $lemma->$method()->word();
        }

        return $wordData[$plugin];
    }

    /**
     * Must parse the lemma.
     *
     * @param string $property
     * @param array  $wordData
     *
     * @return bool
     */
    private function mustParseLemma($property, array $wordData)
    {
        return $property === 'lemma' && $wordData['lemma'] !== $wordData['word'] && $this->hasKanji($wordData['lemma']);
    }

    /**
     * Parse the lemma.
     *
     * @param string $lemma
     *
     * @return LimelightWord
     */
    private function parseLemma($lemma)
    {
        $result = $this->limelight->parse($lemma);

        return $result->pull(0);
    }
}
