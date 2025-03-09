<?php

declare(strict_types=1);

namespace Limelight\tests\Integration;

use Limelight\Classes\LimelightResults;
use Limelight\Classes\LimelightWord;
use Limelight\Config\Config;
use Limelight\Exceptions\PluginNotFoundException;
use Limelight\Tests\TestCase;

class LimelightResultsTest extends TestCase
{
    public function testItCanBeInstantiated(): void
    {
        $results = new LimelightResults('test', ['item', 'another thing'], []);

        $this->assertInstanceOf(LimelightResults::class, $results);
    }

    public function testItCanBeIteratedOver(): void
    {
        foreach ($this->getResults() as $result) {
            $this->assertInstanceOf(LimelightWord::class, $result);
        }
    }

    public function testItPrintsJsonWhenObjectPrinted(): void
    {
        ob_start();

        echo $this->getResults()->only([0]);

        $output = ob_get_clean();

        $this->assertJsonStringEqualsJsonString('[{"rawMecab":[{"type":"parsed","literal":"\u97f3\u697d","partOfSpeech1":"meishi","partOfSpeech2":"\u4e00\u822c","partOfSpeech3":"*","partOfSpeech4":"*","inflectionType":"*","inflectionForm":"*","lemma":"\u97f3\u697d","reading":"\u30aa\u30f3\u30ac\u30af","pronunciation":"\u30aa\u30f3\u30ac\u30af"}],"word":"\u97f3\u697d","lemma":"\u97f3\u697d","reading":"\u30aa\u30f3\u30ac\u30af","pronunciation":"\u30aa\u30f3\u30ac\u30af","partOfSpeech":"noun","grammar":null,"parsed":true,"pluginData":{"Furigana":"<ruby><rb>\u97f3\u697d<\/rb><rp>(<\/rp><rt>\u304a\u3093\u304c\u304f<\/rt><rp>)<\/rp><\/ruby>","Romaji":"ongaku"}}]', $output);
    }

    public function testItCanGetAllLimelightwordObjects(): void
    {
        $words = $this->getResults();

        $this->AssertCount(4, $words);

        $this->assertInstanceOf(LimelightWord::class, $words->first());
    }

    public function testItCanGetOriginalInputString(): void
    {
        $original = $this->getResults()->original();

        $this->assertEquals('音楽を聴きます。', $original);
    }

    public function testItCanBuildAString(): void
    {
        $string = $this->getResults()->string('word');

        $this->assertEquals('音楽を聴きます。', $string);
    }

    public function testItCanBuildAStringDividedBySpaces(): void
    {
        $string = $this->getResults()->string('word', ' ');

        $this->assertEquals('音楽 を 聴きます。', $string);
    }

    public function testItCanBuildAStringDividedByMbSpaces(): void
    {
        $string = $this->getResults()->string('word', ' ');

        $this->assertEquals('音楽 を 聴きます。', $string);
    }

    public function testItCanBuildAStringDividedByNonSpaceCharacter(): void
    {
        $string = $this->getResults()->string('word', '|');

        $this->assertEquals('音楽|を|聴きます|。', $string);
    }

    public function testItCanGetAllWords(): void
    {
        $words = $this->getResults()->words()->all();

        $this->assertEquals(['音楽', 'を', '聴きます', '。'], $words);
    }

    public function testItCanGetAllLemmas(): void
    {
        $lemmas = $this->getResults()->lemmas()->all();

        $this->assertEquals(['音楽', 'を', '聴く', '。'], $lemmas);
    }

    public function testItCanGetAllReadings(): void
    {
        $readings = $this->getResults()->readings()->all();

        $this->assertEquals(['オンガク', 'ヲ', 'キキマス', '。'], $readings);
    }

    public function testItCanGetAllPronunciations(): void
    {
        $pronunciations = $this->getResults()->pronunciations()->all();

        $this->assertEquals(['オンガク', 'ヲ', 'キキマス', '。'], $pronunciations);
    }

    public function testItCanGetAllPartsOfSpeech(): void
    {
        $partsOfSpeech = $this->getResults()->partsOfSpeech()->all();

        $this->assertEquals(['noun', 'postposition', 'verb', 'symbol'], $partsOfSpeech);
    }

    public function testItCanGetRomaji(): void
    {
        $romaji = $this->getResults()->romaji();

        $this->assertEquals(['ongaku', 'o', 'kikimasu', '.'], $romaji->all());
    }

    public function testItCanBuildARomajiStringWithDividingSpace(): void
    {
        $string = $this->getResults()->string('romaji', ' ');

        $this->assertEquals('ongaku o kikimasu.', $string);
    }

    public function testItCanBuildARomajiStringWithDividingChar(): void
    {
        $string = $this->getResults()->string('romaji', '-');

        $this->assertEquals('ongaku-o-kikimasu-.', $string);
    }

    public function testItCanGetFurigana(): void
    {
        $furigana = $this->getResults()->furigana();

        $this->assertEquals([
            '<ruby><rb>音楽</rb><rp>(</rp><rt>おんがく</rt><rp>)</rp></ruby>',
            'を',
            '<ruby><rb>聴</rb><rp>(</rp><rt>き</rt><rp>)</rp></ruby>きます',
            '。',
        ], $furigana->all());
    }

    public function testItCanBuildAFuriganaString(): void
    {
        $string = $this->getResults()->string('furigana');

        $this->assertEquals('<ruby><rb>音楽</rb><rp>(</rp><rt>おんがく</rt><rp>)</rp></ruby>を<ruby><rb>聴</rb><rp>(</rp><rt>き</rt><rp>)</rp></ruby>きます。', $string);
    }

    public function testItCanConvertToHiragana(): void
    {
        $results = $this->getResults()->toHiragana()->readings();

        $this->assertEquals(['おんがく', 'を', 'ききます', '。'], $results->all());
    }

    public function testItCanBuildAHiraganaStringWithSpaces(): void
    {
        $string = $this->getResults()->toHiragana()->string('reading', ' ');

        $this->assertEquals('おんがく を ききます。', $string);
    }

    public function testItCanBuildAHiraganaStringWithDividingChar(): void
    {
        $string = $this->getResults()->toHiragana()->string('reading', '-');

        $this->assertEquals('おんがく-を-ききます-。', $string);
    }

    public function testItCanConvertToKatakana(): void
    {
        $string = $this->getResults()->toKatakana()->string('word');

        $this->assertEquals('音楽ヲ聴キマス。', $string);
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
            $this->getResults()->romaji();
        } finally {
            $config->resetConfig();
        }
    }

    public function testItCanGetPluginData(): void
    {
        $furigana = $this->getResults()->plugin('Furigana');

        $this->assertEquals(
            '<ruby><rb>音楽</rb><rp>(</rp><rt>おんがく</rt><rp>)</rp></ruby>を<ruby><rb>聴</rb><rp>(</rp><rt>き</rt><rp>)</rp></ruby>きます。',
            $furigana);
    }

    public function testItPutsASpaceBeforeSymbolWhenPartOfSpeech(): void
    {
        $string = $this->getResults()->string('partOfSpeech', ' ');

        $this->assertEquals('noun postposition verb symbol', $string);
    }

    public function testItAcceptsPluralStringValues(): void
    {
        $string = $this->getResults()->string('words');

        $this->assertEquals('音楽を聴きます。', $string);
    }

    public function testStringWithGlueDoesNotStartWithGlue(): void
    {
        $string = $this->getResults()->toHiragana()->string('reading', '--');

        $this->assertEquals('おんがく--を--ききます--。', $string);
    }

    /**
     * Parse test phrase and return LimelightResults.
     */
    protected function getResults(): LimelightResults
    {
        return self::$limelight->parse('音楽を聴きます。');
    }
}
