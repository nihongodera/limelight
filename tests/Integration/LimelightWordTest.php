<?php

namespace Limelight\tests\Integration;

use Limelight\Config\Config;
use Limelight\Tests\TestCase;

class LimelightWordTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_get_plugin_data_by_method_call()
    {
        $romaji = $this->getResults()->pull(0)->romaji();

        $this->assertEquals('Tōkyō', $romaji);
    }

    /**
     * @test
     */
    public function it_can_get_plugin_data_by_property_call()
    {
        $romaji = $this->getResults()->pull(0)->romaji;

        $this->assertEquals('Tōkyō', $romaji);
    }

    /**
     * @test
     */
    public function it_can_get_property_by_property_name()
    {
        $word = $this->getResults()->pull(0)->word;

        $this->assertEquals('東京', $word);
    }

    /**
     * @test
     */
    public function it_returns_json_when_object_is_printed()
    {
        $word = $this->getResults()->pull(0);

        ob_start();

        echo $word;

        $output = ob_get_contents();

        ob_end_clean();

        $this->assertJsonStringEqualsJsonString('{"rawMecab":[{"type":"parsed","literal":"\u6771\u4eac","partOfSpeech1":"meishi","partOfSpeech2":"koyuumeishi","partOfSpeech3":"\u5730\u57df","partOfSpeech4":"\u4e00\u822c","inflectionType":"*","inflectionForm":"*","lemma":"\u6771\u4eac","reading":"\u30c8\u30a6\u30ad\u30e7\u30a6","pronunciation":"\u30c8\u30fc\u30ad\u30e7\u30fc"}],"word":"\u6771\u4eac","lemma":"\u6771\u4eac","reading":"\u30c8\u30a6\u30ad\u30e7\u30a6","pronunciation":"\u30c8\u30fc\u30ad\u30e7\u30fc","partOfSpeech":"proper noun","grammar":null,"parsed":true,"pluginData":{"Furigana":"<ruby><rb>\u6771\u4eac<\/rb><rp>(<\/rp><rt>\u3068\u3046\u304d\u3087\u3046<\/rt><rp>)<\/rp><\/ruby>","Romaji":"T\u014dky\u014d"}}', $output);
    }

    /**
     * @test
     */
    public function it_can_get_raw_mecab_data()
    {
        $rawMecab = $this->getResults()->pull(0)->rawMecab();

        $this->assertEquals('東京', $rawMecab[0]['literal']);
    }

    /**
     * @test
     */
    public function it_can_get_word()
    {
        $word = $this->getResults()->pull(0)->word();

        $this->assertEquals('東京', $word);
    }

    /**
     * @test
     */
    public function it_can_get_word_when_get_called()
    {
        $word = $this->getResults()->pull(0)->get();

        $this->assertEquals('東京', $word);
    }

    /**
     * @test
     */
    public function it_can_get_lemma()
    {
        $lemma = $this->getResults()->pull(0)->lemma();

        $this->assertEquals('東京', $lemma);
    }

    /**
     * @test
     */
    public function it_can_get_reading()
    {
        $reading = $this->getResults()->pull(0)->reading();

        $this->assertEquals('トウキョウ', $reading);
    }

    /**
     * @test
     */
    public function it_can_get_pronunciation()
    {
        $pronunciation = $this->getResults()->pull(0)->pronunciation();

        $this->assertEquals('トーキョー', $pronunciation);
    }

    /**
     * @test
     */
    public function it_can_get_partOfSpeech()
    {
        $partOfSpeech = $this->getResults()->pull(0)->partOfSpeech();

        $this->assertEquals('proper noun', $partOfSpeech);
    }

    /**
     * @test
     */
    public function it_can_get_grammar()
    {
        $grammar = $this->getResults()->pull(0)->grammar();

        $this->assertEquals(null, $grammar);
    }

    /**
     * @test
     */
    public function it_can_get_plugin_data()
    {
        $furigana = $this->getResults()->pull(0)->plugin('Furigana');

        $this->AssertEquals('<ruby><rb>東京</rb><rp>(</rp><rt>とうきょう</rt><rp>)</rp></ruby>', $furigana);
    }

    /**
     * @test
     */
    public function it_can_get_romaji()
    {
        $romaji = $this->getResults()->pull(8)->romaji();

        $this->assertEquals('oishikatta', $romaji);
    }

    /**
     * @test
     */
    public function it_can_get_furigana()
    {
        $furigana = $this->getResults()->pull(6)->furigana();

        $this->assertEquals('<ruby><rb>食</rb><rp>(</rp><rt>た</rt><rp>)</rp></ruby>べてしまった', $furigana);
    }

    /**
     * @test
     */
    public function it_can_convert_to_hiragana()
    {
        $reading = $this->getResults()->pull(0)->toHiragana()->reading();

        $this->assertEquals('とうきょう', $reading);
    }

    /**
     * @test
     */
    public function it_can_convert_to_katakana()
    {
        $pronunciation = $this->getResults()->pull(8)->toKatakana()->word();

        $this->assertEquals('オイシカッタ', $pronunciation);
    }

    /**
     * @test
     *
     * @expectedException Limelight\Exceptions\PluginNotFoundException
     * @expectedExceptionMessage Plugin data for Romaji can not be found. Is the Romaji plugin registered in config?
     */
    public function it_throws_exception_when_plugin_not_registered()
    {
        $config = Config::getInstance();

        $config->erase('plugins', 'Romaji');

        $string = $this->getResults()->romaji()->words();
        
        $config->resetConfig();
    }

    /**
     * @test
     */
    public function it_parses_the_lemma()
    {
        $lemma = $this->getResults()->first()->parseLemma();

        $this->assertInstanceOf('Limelight\Classes\LimelightWord', $lemma);

        $this->assertEquals('トウキョウ', $lemma->reading());
    }

    /**
     * @test
     */
    public function it_can_append_to_property()
    {
        $wordObject = $this->getResults()->pull(0);

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
        Config::getInstance()->resetConfig();

        $wordObject = $this->getResults()->pull(0);

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
        $wordObject = $this->getResults()->pull(0);

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
    public function it_gets_romaji_for_nonparsed_kana_words()
    {
        $results = self::$limelight->parse('ロマンティック');

        $result = $results->all()[0];

        $romaji = $result->romaji();

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
    
    /**
     * Parse test phrase and return LimelightResults.
     *
     * @return LimelightResults
     */
    protected function getResults()
    {
        return self::$limelight->parse('東京に行って、パスタを食べてしまった。おいしかったです！');
    }
}
