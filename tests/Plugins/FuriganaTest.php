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

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>燃</rb><rp>(</rp><rt>も</rt><rp>)</rp></ruby>える', $furigana);
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

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('テレビ', $furigana);
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

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('おいしい', $furigana);
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

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>勉強</rb><rp>(</rp><rt>べんきょう</rt><rp>)</rp></ruby>する', $furigana);
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

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('おいしい', $furigana);
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

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>燃</rb><rp>(</rp><rt>も</rt><rp>)</rp></ruby>える', $furigana);
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

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>使</rb><rp>(</rp><rt>つか</rt><rp>)</rp></ruby>い<ruby><rb>方</rb><rp>(</rp><rt>かた</rt><rp>)</rp></ruby>', $furigana);
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

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>健康</rb><rp>(</rp><rt>けんこう</rt><rp>)</rp></ruby>', $furigana);
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

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('ソ<ruby><rb>連</rb><rp>(</rp><rt>れん</rt><rp>)</rp></ruby>', $furigana);
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

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>元気</rb><rp>(</rp><rt>げんき</rt><rp>)</rp></ruby>？', $furigana);
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

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>中傷</rb><rp>(</rp><rt>ちゅうしょう</rt><rp>)</rp></ruby>し', $furigana);
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

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>少々</rb><rp>(</rp><rt>しょうしょう</rt><rp>)</rp></ruby>', $furigana);
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

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>行</rb><rp>(</rp><rt>い</rt><rp>)</rp></ruby>きたい', $furigana);
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

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('アッ、<ruby><rb>太郎</rb><rp>(</rp><rt>たろう</rt><rp>)</rp></ruby>！<ruby><rb>久</rb><rp>(</rp><rt>ひさ</rt><rp>)</rp></ruby>しぶり！<ruby><rb>元気</rb><rp>(</rp><rt>げんき</rt><rp>)</rp></ruby>？', $furigana);
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

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('20<ruby><rb>日</rb><rp>(</rp><rt>にち</rt><rp>)</rp></ruby>', $furigana);
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

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $expected = self::$lib['furigana1Expected'];

        $this->assertEquals($expected, $furigana);
    }

    /**
     * Can get furigana off LimelightResults object.
     *
     * @test
     */
    public function it_can_get_furigana_off_results_object()
    {
        $results = self::$limelight->parse('アッ、太郎！久しぶり！元気？');

        $furigana = $results->plugin('Furigana');

        $this->assertEquals('アッ、<ruby><rb>太郎</rb><rp>(</rp><rt>たろう</rt><rp>)</rp></ruby>！<ruby><rb>久</rb><rp>(</rp><rt>ひさ</rt><rp>)</rp></ruby>しぶり！<ruby><rb>元気</rb><rp>(</rp><rt>げんき</rt><rp>)</rp></ruby>？', $furigana);
    }

    /**
     * Half width numbers have no furigana.
     * 
     * @test
     */
    public function half_width_numbers_have_no_furigana()
    {
        $results = self::$limelight->parse('7時');

        $furigana = $results->plugin('Furigana');

        $this->assertEquals('7<ruby><rb>時</rb><rp>(</rp><rt>じ</rt><rp>)</rp></ruby>', $furigana);
    }

    /**
     * Full width numbers have no furigana.
     * 
     * @test
     */
    public function full_width_numbers_have_no_furigana()
    {
        $results = self::$limelight->parse('７時');

        $furigana = $results->plugin('Furigana');

        $this->assertEquals('７<ruby><rb>時</rb><rp>(</rp><rt>じ</rt><rp>)</rp></ruby>', $furigana);
    }
}
