<?php

declare(strict_types=1);

namespace Limelight\tests\Integration;

use Limelight\Classes\LimelightResults;
use Limelight\Classes\LimelightWord;
use Limelight\Config\Config;
use Limelight\Exceptions\PluginNotFoundException;
use Limelight\Tests\TestCase;

class LimelightWordTest extends TestCase
{
    public function testItCanGetPluginDataByMethodCall(): void
    {
        $romaji = $this->getResults()->pull(0)->romaji();

        $this->assertEquals('Tōkyō', $romaji);
    }

    public function testItCanGetPluginDataByPropertyCall(): void
    {
        $romaji = $this->getResults()->pull(0)->romaji;

        $this->assertEquals('Tōkyō', $romaji);
    }

    public function testItCanGetPropertyByPropertyName(): void
    {
        $word = $this->getResults()->pull(0)->word;

        $this->assertEquals('東京', $word);
    }

    public function testItReturnsJsonWhenObjectIsPrinted(): void
    {
        $word = $this->getResults()->pull(0);

        ob_start();

        echo $word;

        $output = ob_get_clean();

        $this->assertJsonStringEqualsJsonString('{"rawMecab":[{"type":"parsed","literal":"\u6771\u4eac","partOfSpeech1":"meishi","partOfSpeech2":"koyuumeishi","partOfSpeech3":"\u5730\u57df","partOfSpeech4":"\u4e00\u822c","inflectionType":"*","inflectionForm":"*","lemma":"\u6771\u4eac","reading":"\u30c8\u30a6\u30ad\u30e7\u30a6","pronunciation":"\u30c8\u30fc\u30ad\u30e7\u30fc"}],"word":"\u6771\u4eac","lemma":"\u6771\u4eac","reading":"\u30c8\u30a6\u30ad\u30e7\u30a6","pronunciation":"\u30c8\u30fc\u30ad\u30e7\u30fc","partOfSpeech":"proper noun","grammar":null,"parsed":true,"pluginData":{"Furigana":"<ruby><rb>\u6771\u4eac<\/rb><rp>(<\/rp><rt>\u3068\u3046\u304d\u3087\u3046<\/rt><rp>)<\/rp><\/ruby>","Romaji":"T\u014dky\u014d"}}', $output);
    }

    public function testItCanGetRawMecabData(): void
    {
        $rawMecab = $this->getResults()->pull(0)->rawMecab();

        $this->assertEquals('東京', $rawMecab[0]['literal']);
    }

    public function testItCanGetWord(): void
    {
        $word = $this->getResults()->pull(0)->word();

        $this->assertEquals('東京', $word);
    }

    public function testItCanGetWordWhenGetCalled(): void
    {
        $word = $this->getResults()->pull(0)->get();

        $this->assertEquals('東京', $word);
    }

    public function testItCanGetLemma(): void
    {
        $lemma = $this->getResults()->pull(0)->lemma();

        $this->assertEquals('東京', $lemma);
    }

    public function testItCanGetReading(): void
    {
        $reading = $this->getResults()->pull(0)->reading();

        $this->assertEquals('トウキョウ', $reading);
    }

    public function testItCanGetPronunciation(): void
    {
        $pronunciation = $this->getResults()->pull(0)->pronunciation();

        $this->assertEquals('トーキョー', $pronunciation);
    }

    public function testItCanGetPartOfSpeech(): void
    {
        $partOfSpeech = $this->getResults()->pull(0)->partOfSpeech();

        $this->assertEquals('proper noun', $partOfSpeech);
    }

    public function testItCanGetGrammar(): void
    {
        $grammar = $this->getResults()->pull(0)->grammar();

        $this->assertEquals(null, $grammar);
    }

    public function testItCanGetPluginData(): void
    {
        $furigana = $this->getResults()->pull(0)->plugin('Furigana');

        $this->AssertEquals('<ruby><rb>東京</rb><rp>(</rp><rt>とうきょう</rt><rp>)</rp></ruby>', $furigana);
    }

    public function testItCanGetRomaji(): void
    {
        $romaji = $this->getResults()->pull(8)->romaji();

        $this->assertEquals('oishikatta', $romaji);
    }

    public function testItCanGetFurigana(): void
    {
        $furigana = $this->getResults()->pull(6)->furigana();

        $this->assertEquals('<ruby><rb>食</rb><rp>(</rp><rt>た</rt><rp>)</rp></ruby>べてしまった', $furigana);
    }

    public function testItCanConvertToHiragana(): void
    {
        $reading = $this->getResults()->pull(0)->toHiragana()->reading();

        $this->assertEquals('とうきょう', $reading);
    }

    public function testItCanConvertToKatakana(): void
    {
        $pronunciation = $this->getResults()->pull(8)->toKatakana()->word();

        $this->assertEquals('オイシカッタ', $pronunciation);
    }

    public function testItThrowsExceptionWhenPluginNotRegistered(): void
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

    public function testItParsesTheLemma(): void
    {
        $lemma = $this->getResults()->first()->parseLemma();

        $this->assertInstanceOf(LimelightWord::class, $lemma);

        $this->assertEquals('トウキョウ', $lemma->reading());
    }

    public function testItCanAppendToProperty(): void
    {
        $wordObject = $this->getResults()->pull(0);

        $word = $wordObject->word;

        $this->assertEquals('東京', $word);

        $wordObject->appendTo('word', '市');

        $word = $wordObject->word;

        $this->assertEquals('東京市', $word);
    }

    public function testItCanSetPartOfSpeech(): void
    {
        Config::getInstance()->resetConfig();

        $wordObject = $this->getResults()->pull(0);

        $partOfSpeech = $wordObject->partOfSpeech;

        $this->assertEquals('proper noun', $partOfSpeech);

        $wordObject->setPartOfSpeech('test');

        $partOfSpeech = $wordObject->partOfSpeech;

        $this->assertEquals('test', $partOfSpeech);
    }

    public function testItCanSetPluginData(): void
    {
        $wordObject = $this->getResults()->pull(0);

        $romaji = $wordObject->romaji;

        $this->assertEquals('Tōkyō', $romaji);

        $wordObject->setPluginData('Romaji', 'test');

        $romaji = $wordObject->romaji;

        $this->assertEquals('test', $romaji);
    }

    public function testItShowsInfoForNonParsedKanaWords(): void
    {
        $results = self::$limelight->parse('ロマンティック');

        $result = $results->all()[0];

        $reading = $result->reading();

        $pronunciation = $result->pronunciation();

        $this->assertEquals('ロマンティック', $reading);

        $this->assertEquals('ロマンティック', $pronunciation);
    }

    public function testItConvertsReadingToKanaForNonParsedKanaWords(): void
    {
        $results = self::$limelight->parse('ロマンティック');

        $result = $results->all()[0];

        $katakana = $result->toKatakana()->reading();

        $hiragana = $result->toHiragana()->reading();

        $this->assertEquals('ロマンティック', $katakana);

        $this->assertEquals('ろまんてぃっく', $hiragana);
    }

    public function testItConvertsPronunciationToKanaForNonParsedKanaWords(): void
    {
        $results = self::$limelight->parse('ロマンティック');

        $result = $results->all()[0];

        $katakana = $result->toKatakana()->pronunciation();

        $hiragana = $result->toHiragana()->pronunciation();

        $this->assertEquals('ロマンティック', $katakana);

        $this->assertEquals('ろまんてぃっく', $hiragana);
    }

    public function testItGetsRomajiForNonParsedKanaWords(): void
    {
        $results = self::$limelight->parse('ロマンティック');

        $result = $results->all()[0];

        $romaji = $result->romaji();

        $this->assertEquals('Romanthikku', $romaji);
    }

    public function testItShowsParsedWordsAsParsed(): void
    {
        $results = self::$limelight->parse('チケット');

        $result = $results->all()[0];

        $this->assertTrue($result->parsed());
    }

    public function testItShowsNonParsedWordsAsNonParsed(): void
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
