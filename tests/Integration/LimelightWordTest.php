<?php

namespace Limelight\tests\Integration;

use Limelight\Limelight;
use Limelight\Config\Config;
use Limelight\Tests\TestCase;

class LimelightWordTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    protected static $limelight;

    /**
     * @var Limelight\Classes\LimelightResults
     */
    protected static $results;

    /**
     * Set static limelight on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();

        self::$results = self::$limelight->parse('東京に行って、パスタを食べてしまった。おいしかったです！');
    }

    /**
     * @test
     */
    public function it_can_get_plugin_data_by_method_call()
    {
        $romaji = self::$results->findIndex(0)->romaji();

        $this->assertEquals('Tōkyō', $romaji);
    }

    /**
     * @test
     */
    public function it_can_get_plugin_data_by_property_call()
    {
        $romaji = self::$results->findIndex(0)->romaji;

        $this->assertEquals('Tōkyō', $romaji);
    }

    /**
     * @test
     */
    public function it_can_get_property_by_property_name()
    {
        $word = self::$results->findIndex(0)->word;

        $this->assertEquals('東京', $word);
    }

    /**
     * @test
     */
    public function it_can_prints_info_when_object_is_printed()
    {
        $results = self::$results;

        ob_start();

        echo $results;

        $output = ob_get_contents();

        ob_end_clean();

        $this->assertContains('東京', $output);
    }

    /**
     * @test
     */
    public function it_can_get_raw_mecab_data()
    {
        $rawMecab = self::$results->findIndex(0)->rawMecab();

        $this->assertEquals('東京', $rawMecab[0]['literal']);
    }

    /**
     * @test
     */
    public function it_can_get_word()
    {
        $word = self::$results->findIndex(0)->word();

        $this->assertEquals('東京', $word);
    }

    /**
     * @test
     */
    public function it_can_get_lemma()
    {
        $lemma = self::$results->findIndex(0)->lemma();

        $this->assertEquals('東京', $lemma);
    }

    /**
     * @test
     */
    public function it_can_get_reading()
    {
        $reading = self::$results->findIndex(0)->reading();

        $this->assertEquals('トウキョウ', $reading);
    }

    /**
     * @test
     */
    public function it_can_get_pronunciation()
    {
        $pronunciation = self::$results->findIndex(0)->pronunciation();

        $this->assertEquals('トーキョー', $pronunciation);
    }

    /**
     * @test
     */
    public function it_can_get_partOfSpeech()
    {
        $partOfSpeech = self::$results->findIndex(0)->partOfSpeech();

        $this->assertEquals('proper noun', $partOfSpeech);
    }

    /**
     * @test
     */
    public function it_can_get_grammar()
    {
        $grammar = self::$results->findIndex(0)->grammar();

        $this->assertEquals(null, $grammar);
    }

    /**
     * @test
     */
    public function it_can_get_plugin_data()
    {
        $furigana = self::$results->findIndex(0)->plugin('Furigana');

        $this->AssertEquals('<ruby><rb>東京</rb><rp>(</rp><rt>とうきょう</rt><rp>)</rp></ruby>', $furigana);
    }

    /**
     * @test
     */
    public function it_can_convert_to_hiragana()
    {
        $reading = self::$results->findIndex(0)->toHiragana()->reading();

        $this->assertEquals('とうきょう', $reading);
    }

    /**
     * @test
     */
    public function it_can_convert_to_katakana()
    {
        $pronunciation = self::$results->findIndex(8)->toKatakana()->word();

        $this->assertEquals('オイシカッタ', $pronunciation);
    }

    /**
     * @test
     */
    public function it_can_convert_to_romaji()
    {
        $pronunciation = self::$results->findIndex(8)->toRomaji()->word();

        $this->assertEquals('oishikatta', $pronunciation);
    }

    /**
     * @test
     */
    public function it_can_convert_to_furigana()
    {
        $pronunciation = self::$results->findIndex(6)->toFurigana()->lemma();

        $this->assertEquals('<ruby><rb>食</rb><rp>(</rp><rt>た</rt><rp>)</rp></ruby>べる', $pronunciation);
    }

    /**
     * @test
     * @expectedException Limelight\Exceptions\PluginNotFoundException
     * @expectedExceptionMessage Plugin Romaji not found in config.php
     */
    public function it_throws_exception_when_plugin_not_registered()
    {
        $config = Config::getInstance();

        $config->erase('plugins', 'Romaji');

        $string = self::$results->toRomaji()->words();
    }

    /**
     * @test
     */
    public function it_can_append_to_property()
    {
        $wordObject = self::$results->findIndex(0);

        $word = $wordObject->word;

        $this->assertEquals('東京', $word);

        $wordObject->appendTo('word', '市');

        $word = $wordObject->word;

        $this->assertEquals('東京市', $word);
    }

    /**
     * @test
     */
    public function it_can_set_partOfSpeech()
    {
        $wordObject = self::$results->findIndex(0);

        $partOfSpeech = $wordObject->partOfSpeech;

        $this->assertEquals('proper noun', $partOfSpeech);

        $wordObject->setPartOfSpeech('test');

        $partOfSpeech = $wordObject->partOfSpeech;

        $this->assertEquals('test', $partOfSpeech);
    }

    /**
     * @test
     */
    public function it_can_set_plugin_data()
    {
        $wordObject = self::$results->findIndex(0);

        $romaji = $wordObject->romaji;

        $this->assertEquals('Tōkyō', $romaji);

        $wordObject->setPluginData('Romaji', 'test');

        $romaji = $wordObject->romaji;

        $this->assertEquals('test', $romaji);
    }

    /**
     * @test
     */
    public function it_shows_info_for_nonparsed_kana_words()
    {
        $config = Config::getInstance();

        $config->resetConfig();

        $results = self::$limelight->parse('ロマンティック');

        $result = $results->all()[0];

        $reading = $result->reading();
        $pronunciation = $result->pronunciation();

        $this->assertEquals('ロマンティック', $reading);
        $this->assertEquals('ロマンティック', $pronunciation);
    }

    /**
     * @test
     */
    public function it_converts_reading_to_kana_for_nonparsed_kana_words()
    {
        $results = self::$limelight->parse('ロマンティック');

        $result = $results->all()[0];

        $katakana = $result->toKatakana()->reading();
        $hiragana = $result->toHiragana()->reading();

        $this->assertEquals('ロマンティック', $katakana);
        $this->assertEquals('ろまんてぃっく', $hiragana);
    }

    /**
     * @test
     */
    public function it_converts_pronunciation_to_kana_for_nonparsed_kana_words()
    {
        $results = self::$limelight->parse('ロマンティック');

        $result = $results->all()[0];

        $katakana = $result->toKatakana()->pronunciation();
        $hiragana = $result->toHiragana()->pronunciation();

        $this->assertEquals('ロマンティック', $katakana);
        $this->assertEquals('ろまんてぃっく', $hiragana);
    }

    /**
     * @test
     */
    public function it_converts_lemma_to_kana_for_nonparsed_kana_words()
    {
        $results = self::$limelight->parse('ロマンティック');

        $result = $results->all()[0];

        $katakana = $result->toKatakana()->lemma();
        $hiragana = $result->toHiragana()->lemma();

        $this->assertEquals('ロマンティック', $katakana);
        $this->assertEquals('ろまんてぃっく', $hiragana);
    }

    /**
     * @test
     */
    public function it_converts_to_romaji_for_nonparsed_kana_words()
    {
        $results = self::$limelight->parse('ロマンティック');

        $result = $results->all()[0];

        $romaji = $result->toRomaji()->reading();

        $this->assertEquals('Romanthikku', $romaji);
    }

    /**
     * @test
     */
    public function it_shows_parsed_words_as_parsed()
    {
        $results = self::$limelight->parse('チケット');

        $result = $results->all()[0];

        $this->assertTrue($result->parsed());
    }

    /**
     * @test
     */
    public function it_shows_nonparsed_words_as_nonparsed()
    {
        $results = self::$limelight->parse('矮星');

        $result = $results->all()[0];

        $this->assertFalse($result->parsed());
    }
}
