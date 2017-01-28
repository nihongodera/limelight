<?php

namespace Limelight\Plugins\Library\Furigana;

use Limelight\Config\Config;
use Limelight\Plugins\Plugin;
use Limelight\Helpers\JapaneseHelpers;

class Furigana extends Plugin
{
    use JapaneseHelpers;

    /**
     * Tags pulled from config file.
     *
     * @var array
     */
    private $tags = [];

    /**
     * Construct.
     *
     * @param string $text
     * @param Node   $node
     * @param array  $tokens
     * @param array  $words
     */
    public function __construct($text, $node, $tokens, $words)
    {
        parent::__construct($text, $node, $tokens, $words);

        $this->setTags();
    }

    /**
     * Run the plugin.
     *
     * @return mixed
     */
    public function handle()
    {
        $furiganaString = '';

        foreach ($this->words as $wordObject) {
            $furiganaWord = '';

            $word = $wordObject->word();

            $wordChars = $this->getChars($word);

            $katakanaChars = $this->getChars($wordObject->reading());

            $hiraganaChars = $this->buildHiraganaChars($wordChars, $katakanaChars);

            $kanjiArray = $this->purgeKatakana(array_diff($wordChars, $hiraganaChars));

            $kanaArray = $this->buildKanaArray($hiraganaChars, $wordChars);

            $kanji = $this->divide($kanjiArray);

            $kana = $this->divide($kanaArray);

            $kanjiWithKana = $this->combineKanjiKana($kanji, $kana);

            $furiganaWord .= $this->rebuildWord($wordChars, $kanjiWithKana, $katakanaChars);

            $this->addToWord($wordObject, $furiganaWord);

            $furiganaString .= $furiganaWord;
        }

        return $furiganaString;
    }

    /**
     * Build array of hiragana chars for furigana.
     *
     * @param array $wordChars
     * @param array $katakanaChars
     *
     * @return array
     */
    private function buildHiraganaChars(array $wordChars, array $katakanaChars)
    {
        $results = [];

        foreach (array_diff($katakanaChars, $wordChars) as $key => $value) {
            $results[$key] = mb_convert_kana($value, 'c');
        }

        return $results;
    }

    /**
     * Remove katakan from array.
     *
     * @param array $array
     *
     * @return array
     */
    private function purgeKatakana(array $array)
    {
        return array_filter($array, function ($value) {
            return !$this->isKatakana($value) && $this->hasKanji($value);
        });
    }

    /**
     * Build array of kana characters for furigana.
     *
     * @param array $hiraganaChars
     * @param array $wordChars
     *
     * @return array
     */
    private function buildKanaArray(array $hiraganaChars, array $wordChars)
    {
        $intersect = array_intersect($wordChars, $hiraganaChars);

        foreach ($hiraganaChars as $hiraganaChar) {
            if ($this->shouldReverseCompile($hiraganaChars, $hiraganaChar, $intersect)) {
                return $this->reverseArrayCompile($wordChars, $hiraganaChars);
            }
        }

        return array_diff($hiraganaChars, $wordChars);
    }

    /**
     * Return true if the chars should be reverse compiled.
     *
     * @param array  $hiraganaChars
     * @param string $hiraganaChar
     * @param array  $intersect
     *
     * @return bool
     */
    protected function shouldReverseCompile($hiraganaChars, $hiraganaChar, $intersect)
    {
        return $this->countArrayValues($hiraganaChars, $hiraganaChar) !== 1 &&
            !empty($intersect) &&
            in_array($hiraganaChar, $intersect);
    }

    /**
     * Return number of instances of value in array.
     *
     * @param array  $array
     * @param string $value
     *
     * @return int
     */
    private function countArrayValues(array $array, $value)
    {
        $counts = array_count_values($array);

        return $counts[$value];
    }

    /**
     * Find valid furigana by walking hiragana array in reverse.
     *
     * @param array $wordChars
     * @param array $hiraganaChars
     *
     * @return array
     */
    private function reverseArrayCompile(array $wordChars, array $hiraganaChars)
    {
        $reverseHiragana = array_reverse($hiraganaChars);

        $reverseHiraganaCopy = $reverseHiragana;

        foreach ($reverseHiragana as $key => $char) {
            if (in_array($char, $wordChars)) {
                unset($wordChars[array_search($char, $wordChars)]);

                unset($reverseHiraganaCopy[$key]);
            }
        }

        return array_diff(array_reverse($reverseHiraganaCopy), $wordChars);
    }

    /**
     * Divide array into arrays of continuous keys.
     *
     * @param array $subject
     *
     * @return array
     */
    private function divide(array $subject)
    {
        $result = [];

        $count = key($subject);

        $index = $count;

        foreach ($subject as $key => $value) {
            if ($key === $count) {
                if (!isset($result[$index])) {
                    $result[$index] = '';
                }

                $result[$index] .= $value;

                $count += 1;
            } elseif ($key !== $count) {
                $index = $key;

                $count = $key + 1;

                $result[$index] = $value;
            }
        }

        return $result;
    }

    /**
     * Combine kanji with its furigana.
     *
     * @param array $kanjiArray
     * @param array $furiganaArray
     *
     * @return array
     */
    private function combineKanjiKana(array $kanjiArray, array $furiganaArray)
    {
        $results = [];

        foreach ($kanjiArray as $key => $kanji) {
            $furigana = array_shift($furiganaArray);

            $results[$key] = $this->buildFurigana($kanji, $furigana);
        }

        return $results;
    }

    /**
     * Wrap tags around kanji and furigana.
     *
     * @param string $kanji
     * @param string $furigana
     *
     * @return string
     */
    private function buildFurigana($kanji, $furigana)
    {
        return $this->tags['kanji_furigana_wrapper']['open'].
               $this->tags['kanji_wrapper']['open'].
               $kanji.
               $this->tags['kanji_wrapper']['close'].
               $this->tags['furigana_wrapper']['open'].
               $furigana.
               $this->tags['furigana_wrapper']['close'].
               $this->tags['kanji_furigana_wrapper']['close'];
    }

    /**
     * Rebuild word with furigana.
     *
     * @param array $wordChars
     * @param array $kanjiWithKana
     * @param array $katakanaChars
     *
     * @return string
     */
    private function rebuildWord(array $wordChars, array $kanjiWithKana, array $katakanaChars)
    {
        $word = '';

        foreach ($wordChars as $key => $char) {
            if ($this->shouldAddKanji($kanjiWithKana, $katakanaChars, $char, $key)) {
                $word .= $kanjiWithKana[$key];

                unset($kanjiWithKana[$key]);

                continue;
            } elseif (!$this->hasKanji($char)) {
                $word .= $char;
            }
        }

        if (!empty($kanjiWithKana)) {
            $word .= implode('', $kanjiWithKana);
        }

        return $word;
    }

    /**
     * Return true if kanji with kana should be added to word.
     *
     * @param array  $kanjiWithKana
     * @param array  $katakanaChars
     * @param string $char
     * @param int    $key
     *
     * @return bool
     */
    protected function shouldAddKanji($kanjiWithKana, $katakanaChars, $char, $key)
    {
        return isset($kanjiWithKana[$key]) &&
            isset($katakanaChars[$key]) &&
            $char !== $katakanaChars[$key];
    }

    /**
     * Add furigana word to word object.
     *
     * @param LimelightWord $wordObject
     * @param string        $word
     */
    private function addToWord($wordObject, $word)
    {
        $word = $this->tags['word_wrapper']['open'].
            $word.$this->tags['word_wrapper']['close'];

        $wordObject->setPluginData('Furigana', $word);
    }

    /**
     * Set user defined tags on object.
     */
    private function setTags()
    {
        $config = Config::getInstance();

        $tags = $config->get('Furigana');

        foreach ($tags as $name => $tag) {
            $openClose = explode('{{}}', $tag);

            $this->tags[$name] = [
                'open'  => (isset($openClose[0]) ? $openClose[0] : ''),
                'close' => (isset($openClose[1]) ? $openClose[1] : ''),
            ];
        }
    }
}
