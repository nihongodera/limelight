<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\Plugins\Furigana;

use Limelight\Tests\TestCase;

class FuriganaTest extends TestCase
{
    private static array $lib;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$lib = include 'tests/lib.php';
    }

    public function testItAddsKanjiToStringForSingleKanjiWord(): void
    {
        $results = self::$limelight->parse('燃える');

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>燃</rb><rp>(</rp><rt>も</rt><rp>)</rp></ruby>える', $furigana);
    }

    public function testItPassesKatakanaWordsUntouched(): void
    {
        $results = self::$limelight->parse('テレビ');

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('テレビ', $furigana);
    }

    public function testItPassesHiraganaWordsUntouched(): void
    {
        $results = self::$limelight->parse('おいしい');

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('おいしい', $furigana);
    }

    public function testItAddsKanjiToStringForDoubleKanjiWord(): void
    {
        $results = self::$limelight->parse('勉強する');

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>勉強</rb><rp>(</rp><rt>べんきょう</rt><rp>)</rp></ruby>する', $furigana);
    }

    public function testItAddsKanaToStringForKanaOnlyWord(): void
    {
        $results = self::$limelight->parse('おいしい');

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('おいしい', $furigana);
    }

    public function testItMakesFuriganaForKanjiKanaWord(): void
    {
        $results = self::$limelight->parse('燃える');

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>燃</rb><rp>(</rp><rt>も</rt><rp>)</rp></ruby>える', $furigana);
    }

    public function testItMakesFuriganaForKanjiKanaKanjiWord(): void
    {
        $results = self::$limelight->parse('使い方');

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>使</rb><rp>(</rp><rt>つか</rt><rp>)</rp></ruby>い<ruby><rb>方</rb><rp>(</rp><rt>かた</rt><rp>)</rp></ruby>', $furigana);
    }

    public function testItMakesFuriganaForKanjiKanjiWord(): void
    {
        $results = self::$limelight->parse('健康');

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>健康</rb><rp>(</rp><rt>けんこう</rt><rp>)</rp></ruby>', $furigana);
    }

    public function testItMakesFuriganaForKanaKanjiWord(): void
    {
        $results = self::$limelight->parse('ソ連');

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('ソ<ruby><rb>連</rb><rp>(</rp><rt>れん</rt><rp>)</rp></ruby>', $furigana);
    }

    public function testItMakesFuriganaForKanjiPuncWord(): void
    {
        $results = self::$limelight->parse('元気？');

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>元気</rb><rp>(</rp><rt>げんき</rt><rp>)</rp></ruby>？', $furigana);
    }

    public function testItMakesFuriganaForWordWhenSameHiraganaAppears1(): void
    {
        $results = self::$limelight->parse('中傷し');

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>中傷</rb><rp>(</rp><rt>ちゅうしょう</rt><rp>)</rp></ruby>し', $furigana);
    }

    public function testItMakesFuriganaForWordWhenSameHiraganaAppears2(): void
    {
        $results = self::$limelight->parse('少々');

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>少々</rb><rp>(</rp><rt>しょうしょう</rt><rp>)</rp></ruby>', $furigana);
    }

    public function testItMakesFuriganaForWordWhenSameHiraganaAppears3(): void
    {
        $results = self::$limelight->parse('行きたい');

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>行</rb><rp>(</rp><rt>い</rt><rp>)</rp></ruby>きたい', $furigana);
    }

    public function testItMakesFuriganaForComplexPhrase(): void
    {
        $results = self::$limelight->parse('アッ、太郎！久しぶり！元気？');

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('アッ、<ruby><rb>太郎</rb><rp>(</rp><rt>たろう</rt><rp>)</rp></ruby>！<ruby><rb>久</rb><rp>(</rp><rt>ひさ</rt><rp>)</rp></ruby>しぶり！<ruby><rb>元気</rb><rp>(</rp><rt>げんき</rt><rp>)</rp></ruby>？', $furigana);
    }

    public function testItMakesFuriganaForNumberKanjiCombos(): void
    {
        $results = self::$limelight->parse('20日');

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('20<ruby><rb>日</rb><rp>(</rp><rt>にち</rt><rp>)</rp></ruby>', $furigana);
    }

    public function testItMakesFuriganaForCompleteArticle(): void
    {
        $results = self::$limelight->parse(self::$lib['furigana1']);

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $expected = self::$lib['furigana1Expected'];

        $this->assertEquals($expected, $furigana);
    }

    public function testItCanGetFuriganaOffResultsObject(): void
    {
        $results = self::$limelight->parse('アッ、太郎！久しぶり！元気？');

        $furigana = $results->plugin('Furigana');

        $this->assertEquals(
            'アッ、<ruby><rb>太郎</rb><rp>(</rp><rt>たろう</rt><rp>)</rp></ruby>！<ruby><rb>久</rb><rp>(</rp><rt>ひさ</rt><rp>)</rp></ruby>しぶり！<ruby><rb>元気</rb><rp>(</rp><rt>げんき</rt><rp>)</rp></ruby>？',
            $furigana);
    }

    public function testItDoesntMakeFuriganaForHalfWidthNumbers(): void
    {
        $results = self::$limelight->parse('7時');

        $furigana = $results->plugin('Furigana');

        $this->assertEquals('7<ruby><rb>時</rb><rp>(</rp><rt>じ</rt><rp>)</rp></ruby>', $furigana);
    }

    public function testItDoesntMakeFuriganaForFullWidthNumbers(): void
    {
        $results = self::$limelight->parse('７時');

        $furigana = $results->plugin('Furigana');

        $this->assertEquals('７<ruby><rb>時</rb><rp>(</rp><rt>じ</rt><rp>)</rp></ruby>', $furigana);
    }
}
