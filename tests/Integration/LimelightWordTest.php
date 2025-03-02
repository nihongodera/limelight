<?php

declare(strict_types=1);

namespace Limelight\tests\Integration;

use Limelight\Config\Config;
use Limelight\Tests\TestCase;
use Limelight\Classes\LimelightWord;
use Limelight\Classes\LimelightResults;
use Limelight\Exceptions\PluginNotFoundException;

class LimelightWordTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_get_plugin_data_by_method_call(): void
    {
        $romaji = $this->getResults()->pull(0)->romaji();

        $this->assertEquals('Tōkyō', $romaji);
    }

    /**
     * @test
     */
    public function it_can_get_plugin_data_by_property_call(): void
    {
        $romaji = $this->getResults()->pull(0)->romaji;

        $this->assertEquals('Tōkyō', $romaji);
    }

    /**
     * @test
     */
    public function it_can_get_property_by_property_name(): void
    {
        $word = $this->getResults()->pull(0)->word;

        $this->assertEquals('東京', $word);
    }

    /**
     * @test
     */
    public function it_returns_json_when_object_is_printed(): void
    {
        $word = $this->getResults()->pull(0);

        ob_start();

        echo $word;

        $output = ob_get_clean();

        $this->assertJsonStringEqualsJsonString('{"rawMecab":[{"type":"parsed","literal":"\u6771\u4eac","partOfSpeech1":"meishi","partOfSpeech2":"koyuumeishi","partOfSpeech3":"\u5730\u57df","partOfSpeech4":"\u4e00\u822c","inflectionType":"*","inflectionForm":"*","lemma":"\u6771\u4eac","reading":"\u30c8\u30a6\u30ad\u30e7\u30a6","pronunciation":"\u30c8\u30fc\u30ad\u30e7\u30fc"}],"word":"\u6771\u4eac","lemma":"\u6771\u4eac","reading":"\u30c8\u30a6\u30ad\u30e7\u30a6","pronunciation":"\u30c8\u30fc\u30ad\u30e7\u30fc","partOfSpeech":"proper noun","grammar":null,"parsed":true,"pluginData":{"Furigana":"<ruby><rb>\u6771\u4eac<\/rb><rp>(<\/rp><rt>\u3068\u3046\u304d\u3087\u3046<\/rt><rp>)<\/rp><\/ruby>","Romaji":"T\u014dky\u014d"}}', $output);
    }

    /**
     * @test
     */
    public function it_can_get_raw_mecab_data(): void
    {
        $rawMecab = $this->getResults()->pull(0)->rawMecab();

        $this->assertEquals('東京', $rawMecab[0]['literal']);
    }

    /**
     * @test
     */
    public function it_can_get_word(): void
    {
        $word = $this->getResults()->pull(0)->word();

        $this->assertEquals('東京', $word);
    }

    /**
     * @test
     */
    public function it_can_get_word_when_get_called(): void
    {
        $word = $this->getResults()->pull(0)->get();

        $this->assertEquals('東京', $word);
    }

    /**
     * @test
     */
    public function it_can_get_lemma(): void
    {
        $lemma = $this->getResults()->pull(0)->lemma();

        $this->assertEquals('東京', $lemma);
    }

    /**
     * @test
     */
    public function it_can_get_reading(): void
    {
        $reading = $this->getResults()->pull(0)->reading();

        $this->assertEquals('トウキョウ', $reading);
    }

    /**
     * @test
     */
    public function it_can_get_pronunciation(): void
    {
        $pronunciation = $this->getResults()->pull(0)->pronunciation();

        $this->assertEquals('トーキョー', $pronunciation);
    }

    /**
     * @test
     */
    public function it_can_get_partOfSpeech(): void
    {
        $partOfSpeech = $this->getResults()->pull(0)->partOfSpeech();

        $this->assertEquals('proper noun', $partOfSpeech);
    }

    /**
     * @test
     */
    public function it_can_get_grammar(): void
    {
        $grammar = $this->getResults()->pull(0)->grammar();

        $this->assertEquals(null, $grammar);
    }

    /**
     * @test
     */
    public function it_can_get_plugin_data(): void
    {
        $furigana = $this->getResults()->pull(0)->plugin('Furigana');

        $this->AssertEquals('<ruby><rb>東京</rb><rp>(</rp><rt>とうきょう</rt><rp>)</rp></ruby>', $furigana);
    }

    /**
     * @test
     */
    public function it_can_get_romaji(): void
    {
        $romaji = $this->getResults()->pull(8)->romaji();

        $this->assertEquals('oishikatta', $romaji);
    }

    /**
     * @test
     */
    public function it_can_get_furigana(): void
    {
        $furigana = $this->getResults()->pull(6)->furigana();

        $this->assertEquals('<ruby><rb>食</rb><rp>(</rp><rt>た</rt><rp>)</rp></ruby>べてしまった', $furigana);
    }

    /**
     * @test
     */
    public function it_can_convert_to_hiragana(): void
    {
        $reading = $this->getResults()->pull(0)->toHiragana()->reading();

        $this->assertEquals('とうきょう', $reading);
    }

    /**
     * @test
     */
    public function it_can_convert_to_katakana(): void
    {
        $pronunciation = $this->getResults()->pull(8)->toKatakana()->word();

        $this->assertEquals('オイシカッタ', $pronunciation);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_plugin_not_registered(): void
    {
        $this->expectExceptionMessage(
            'Plugin data for Romaji can not be found. Is the Romaji plugin registered in config?'
        );
        $this->expectException(PluginNotFoundException::class);

        $config = Config::getInstance();

        $config->erase('plugins', 'Romaji');

        try {
            $this->getResults()->romaji()->words();
        } finally {
            $config->resetConfig();
        }
    }

    /**
     * @test
     */
    public function it_parses_the_lemma(): void
    {
        $lemma = $this->getResults()->first()->parseLemma();

        $this->assertInstanceOf(LimelightWord::class, $lemma);

        $this->assertEquals('トウキョウ', $lemma->reading());
    }

    /**
     * @test
     */
    public function it_can_append_to_property(): void
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
    public function it_can_set_partOfSpeech(): void
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
    public function it_can_set_plugin_data(): void
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
    public function it_shows_info_for_nonparsed_kana_words(): void
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
    public function it_converts_reading_to_kana_for_nonparsed_kana_words(): void
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
    public function it_converts_pronunciation_to_kana_for_nonparsed_kana_words(): void
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
    public function it_gets_romaji_for_nonparsed_kana_words(): void
    {
        $results = self::$limelight->parse('ロマンティック');

        $result = $results->all()[0];

        $romaji = $result->romaji();

        $this->assertEquals('Romanthikku', $romaji);
    }

    /**
     * @test
     */
    public function it_shows_parsed_words_as_parsed(): void
    {
        $results = self::$limelight->parse('チケット');

        $result = $results->all()[0];

        $this->assertTrue($result->parsed());
    }

    /**
     * @test
     */
    public function it_shows_nonparsed_words_as_nonparsed(): void
    {
        $results = self::$limelight->parse('矮星');

        $result = $results->all()[0];

        $this->assertFalse($result->parsed());
    }

    /**
     * Parse test phrase and return LimelightResults.
     */
    protected function getResults(): LimelightResults
    {
        return self::$limelight->parse('東京に行って、パスタを食べてしまった。おいしかったです！');
    }
}
