<?php

declare(strict_types=1);

namespace Limelight\Plugins\Library\Furigana;

use Limelight\Mecab\Node;
use Limelight\Config\Config;
use Limelight\Plugins\Plugin;
use Limelight\Classes\LimelightWord;
use Limelight\Helpers\JapaneseHelpers;

class Furigana extends Plugin
{
    use JapaneseHelpers;

    /**
     * Tags pulled from config file.
     */
    private array $tags = [];

    public function __construct(string $text, ?Node $node, array $tokens, array $words)
    {
        parent::__construct($text, $node, $tokens, $words);

        $this->setTags();
    }

    /**
     * Run the plugin.
     */
    public function handle(): string
    {
        $furiganaString = '';

        foreach ($this->words as $wordObject) {
            $furiganaWord = '';

            $word = $wordObject->word();

            $wordChars = $this->getChars($word);

            $katakanaChars = $wordObject->reading() ? $this->getChars($wordObject->reading()) : [];

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
     */
    private function buildHiraganaChars(array $wordChars, array $katakanaChars): array
    {
        $results = [];

        foreach (array_diff($katakanaChars, $wordChars) as $key => $value) {
            $results[$key] = mb_convert_kana($value, 'c');
        }

        return $results;
    }

    /**
     * Remove katakana from array.
     */
    private function purgeKatakana(array $array): array
    {
        return array_filter($array, function ($value) {
            return !$this->isKatakana($value) && $this->hasKanji($value);
        });
    }

    /**
     * Build array of kana characters for furigana.
     */
    private function buildKanaArray(array $hiraganaChars, array $wordChars): array
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
     */
    protected function shouldReverseCompile(array $hiraganaChars, string $hiraganaChar, array $intersect): bool
    {
        return $this->countArrayValues($hiraganaChars, $hiraganaChar) !== 1 &&
            !empty($intersect) &&
            in_array($hiraganaChar, $intersect, true);
    }

    /**
     * Return number of instances of value in array.
     */
    private function countArrayValues(array $array, string $value): int
    {
        $counts = array_count_values($array);

        return $counts[$value];
    }

    /**
     * Find valid furigana by walking hiragana array in reverse.
     */
    private function reverseArrayCompile(array $wordChars, array $hiraganaChars): array
    {
        $reverseHiragana = array_reverse($hiraganaChars);

        $reverseHiraganaCopy = $reverseHiragana;

        foreach ($reverseHiragana as $key => $char) {
            if (in_array($char, $wordChars, true)) {
                unset($wordChars[array_search($char, $wordChars, true)], $reverseHiraganaCopy[$key]);
            }
        }

        return array_diff(array_reverse($reverseHiraganaCopy), $wordChars);
    }

    /**
     * Divide array into arrays of continuous keys.
     */
    private function divide(array $subject): array
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

                $count++;
            } else {
                $index = $key;

                $count = $key + 1;

                $result[$index] = $value;
            }
        }

        return $result;
    }

    /**
     * Combine kanji with its furigana.
     */
    private function combineKanjiKana(array $kanjiArray, array $furiganaArray): array
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
     */
    private function buildFurigana(string $kanji, ?string $furigana): string
    {
        return $this->tags['kanji_furigana_wrapper']['open'].
            $this->tags['kanji_wrapper']['open'].
            $kanji.
            $this->tags['kanji_wrapper']['close'].
            $this->tags['furigana_wrapper']['open'].
            ($furigana ?? '').
            $this->tags['furigana_wrapper']['close'].
            $this->tags['kanji_furigana_wrapper']['close'];
    }

    /**
     * Rebuild word with furigana.
     */
    private function rebuildWord(array $wordChars, array $kanjiWithKana, array $katakanaChars): string
    {
        $word = '';

        foreach ($wordChars as $key => $char) {
            if ($this->shouldAddKanji($kanjiWithKana, $katakanaChars, $char, $key)) {
                $word .= $kanjiWithKana[$key];

                unset($kanjiWithKana[$key]);

                continue;
            }

            if (!$this->hasKanji($char)) {
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
     */
    protected function shouldAddKanji(array $kanjiWithKana, array $katakanaChars, string $char, int $key): bool
    {
        return isset($kanjiWithKana[$key], $katakanaChars[$key]) && $char !== $katakanaChars[$key];
    }

    /**
     * Add furigana word to word object.
     */
    private function addToWord(LimelightWord $wordObject, string $word): void
    {
        $word = $this->tags['word_wrapper']['open'].
            $word.
            $this->tags['word_wrapper']['close'];

        $wordObject->setPluginData('Furigana', $word);
    }

    /**
     * Set user defined tags on object.
     */
    private function setTags(): void
    {
        $config = Config::getInstance();

        $tags = $config->get('Furigana');

        foreach ($tags as $name => $tag) {
            $openClose = explode('{{}}', $tag);

            $this->tags[$name] = [
                'open'  => $openClose[0] ?? '',
                'close' => $openClose[1] ?? '',
            ];
        }
    }
}
