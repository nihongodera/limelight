<?php

namespace Limelight\Tests\partOfSpeech;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class FuriganaTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    private static $limelight;

    /**
     * @var array
     */
    private static $lib;

    /**
     * Set Limelight on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();

        self::$lib = include 'tests/lib.php';
    }

    /**
     * Kanji from single kanji word makes it to output.
     * 
     * @test
     */
    public function it_adds_kanji_to_string_for_single_kanji_word()
    {
        $results = self::$limelight->parse('燃える');

        $furigana = '';

        foreach ($results->getNext() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertContains('<ruby>燃<rt>', $furigana);
    }

    /**
     * It does nothing to katakana words.
     * 
     * @test
     */
    public function it_passes_katakana_words_untouched()
    {
        $results = self::$limelight->parse('テレビ');

        $furigana = '';

        foreach ($results->getNext() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertContains('テレビ', $furigana);
    }

    /**
     * It does nothing to hiragana words.
     * 
     * @test
     */
    public function it_passes_hiragana_words_untouched()
    {
        $results = self::$limelight->parse('おいしい');

        $furigana = '';

        foreach ($results->getNext() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertContains('おいしい', $furigana);
    }

    /**
     * Kanji from double kanji word makes it to output.
     * 
     * @test
     */
    public function it_adds_kanji_to_string_for_double_kanji_word()
    {
        $results = self::$limelight->parse('勉強する');

        $furigana = '';

        foreach ($results->getNext() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertContains('<ruby>勉強<rt>', $furigana);
    }

    /**
     * Kana from kana word makes it to output.
     * 
     * @test
     */
    public function it_adds_kana_to_string_for_kana_only_word()
    {
        $results = self::$limelight->parse('おいしい');

        $furigana = '';

        foreach ($results->getNext() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertContains('おいしい', $furigana);
    }

    /**
     * Returns a string with furigana for a kanji-kana.
     * 
     * @test
     */
    public function it_makes_furigana_for_kanji_kana_word()
    {
        $results = self::$limelight->parse('燃える');

        $furigana = '';

        foreach ($results->getNext() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby>燃<rt>も</rt></ruby>える', $furigana);
    }

    /**
     * Returns a string with furigana for a kanji-kana-kanji word.
     *  
     * @test
     */
    public function it_makes_furigana_for_kanji_kana_kanji_word()
    {
        $results = self::$limelight->parse('使い方');

        $furigana = '';

        foreach ($results->getNext() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby>使<rt>つか</rt></ruby>い<ruby>方<rt>かた</rt></ruby>', $furigana);
    }

    /**
     * Returns a string with furigana for a kanji-kanji word.
     *  
     * @test
     */
    public function it_makes_furigana_for_kanji_kanji_word()
    {
        $results = self::$limelight->parse('健康');

        $furigana = '';

        foreach ($results->getNext() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby>健康<rt>けんこう</rt></ruby>', $furigana);
    }

    /**
     * Returns a string with furigana for a kana-kanji word.
     *  
     * @test
     */
    public function it_makes_furigana_for_kana_kanji_word()
    {
        $results = self::$limelight->parse('ソ連');

        $furigana = '';

        foreach ($results->getNext() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('ソ<ruby>連<rt>れん</rt></ruby>', $furigana);
    }

    /**
     * Returns a string with furigana for a kanji-punc word.
     *  
     * @test
     */
    public function it_makes_furigana_for_kanji_punc_word()
    {
       $results = self::$limelight->parse('元気？');

        $furigana = '';

        foreach ($results->getNext() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby>元気<rt>げんき</rt></ruby>？', $furigana);
    }

    /**
     * Returns proper furigana when multiple instances of same hiragana appear.
     * 
     * @test
     */
    public function it_makes_furigana_for_word_when_same_hiragana_appears_1()
    {
        $results = self::$limelight->parse('中傷し');

        $furigana = '';

        foreach ($results->getNext() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby>中傷<rt>ちゅうしょう</rt></ruby>し', $furigana);
    }

    /**
     * Returns proper furigana when multiple instances of same hiragana appear.
     * 
     * @test
     */
    public function it_makes_furigana_for_word_when_same_hiragana_appears_2()
    {
        $results = self::$limelight->parse('少々');

        $furigana = '';

        foreach ($results->getNext() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby>少々<rt>しょうしょう</rt></ruby>', $furigana);
    }

    /**
     * Returns proper furigana when multiple instances of same hiragana appear.
     * 
     * @test
     */
    public function it_makes_furigana_for_word_when_same_hiragana_appears_3()
    {
        $results = self::$limelight->parse('行きたい');

        $furigana = '';

        foreach ($results->getNext() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby>行<rt>い</rt></ruby>きたい', $furigana);
    }

    /**
     * Returns a string with furigana for a complex phrase.
     *  
     * @test
     */
    public function it_makes_furigana_for_complex_phrase()
    {
        $results = self::$limelight->parse('アッ、太郎！久しぶり！元気？');

        $furigana = '';

        foreach ($results->getNext() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('アッ、<ruby>太郎<rt>たろう</rt></ruby>！<ruby>久<rt>ひさ</rt></ruby>しぶり！<ruby>元気<rt>げんき</rt></ruby>？', $furigana);
    }

    /**
     * Number kanji combos are ok.
     * 
     * @test
     */
    public function it_makes_furigana_for_number_kanji_combos()
    {
        $results = self::$limelight->parse('20日');

        $furigana = '';

        foreach ($results->getNext() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('20<ruby>日<rt>にち</rt></ruby>', $furigana);
    }

    /**
     * Returns a string with furigana for a complete article.
     *  
     * @test
     */
    public function it_makes_furigana_for_complete_article()
    {
        $results = self::$limelight->parse(self::$lib['furigana1']);

        $furigana = '';

        foreach ($results->getNext() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $expected = self::$lib['furigana1Expected'];

        $this->assertEquals($expected, $furigana);
    }
}
