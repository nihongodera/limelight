<?php

declare(strict_types=1);

namespace Limelight\tests\Integration;

use Limelight\Config\Config;
use Limelight\Tests\TestCase;
use Limelight\Classes\LimelightWord;
use Limelight\Classes\LimelightResults;
use Limelight\Exceptions\PluginNotFoundException;

class LimelightResultsTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_instantiated(): void
    {
        $results = new LimelightResults('test', ['item', 'another thing'], []);

        $this->assertInstanceOf(LimelightResults::class, $results);
    }

    /**
     * @test
     */
    public function it_can_be_iterated_over(): void
    {
        foreach ($this->getResults() as $result) {
            $this->assertInstanceOf(LimelightWord::class, $result);
        }
    }

    /**
     * @test
     */
    public function it_prints_json_when_object_printed(): void
    {
        ob_start();

        echo $this->getResults()->only([0]);

        $output = ob_get_clean();

        $this->assertJsonStringEqualsJsonString('[{"rawMecab":[{"type":"parsed","literal":"\u97f3\u697d","partOfSpeech1":"meishi","partOfSpeech2":"\u4e00\u822c","partOfSpeech3":"*","partOfSpeech4":"*","inflectionType":"*","inflectionForm":"*","lemma":"\u97f3\u697d","reading":"\u30aa\u30f3\u30ac\u30af","pronunciation":"\u30aa\u30f3\u30ac\u30af"}],"word":"\u97f3\u697d","lemma":"\u97f3\u697d","reading":"\u30aa\u30f3\u30ac\u30af","pronunciation":"\u30aa\u30f3\u30ac\u30af","partOfSpeech":"noun","grammar":null,"parsed":true,"pluginData":{"Furigana":"<ruby><rb>\u97f3\u697d<\/rb><rp>(<\/rp><rt>\u304a\u3093\u304c\u304f<\/rt><rp>)<\/rp><\/ruby>","Romaji":"ongaku"}}]', $output);
    }

    /**
     * @test
     */
    public function it_can_get_all_limelightword_objects(): void
    {
        $words = $this->getResults();

        $this->AssertCount(4, $words);

        $this->assertInstanceOf(LimelightWord::class, $words->first());
    }

    /**
     * @test
     */
    public function it_can_get_original_input_string(): void
    {
        $original = $this->getResults()->original();

        $this->assertEquals('音楽を聴きます。', $original);
    }

    /**
     * @test
     */
    public function it_can_build_a_string(): void
    {
        $string = $this->getResults()->string('word');

        $this->assertEquals('音楽を聴きます。', $string);
    }

    /**
     * @test
     */
    public function it_can_build_a_string_divided_by_spaces(): void
    {
        $string = $this->getResults()->string('word', ' ');

        $this->assertEquals('音楽 を 聴きます。', $string);
    }

    /**
     * @test
     */
    public function it_can_build_a_string_divided_by_mb_spaces(): void
    {
        $string = $this->getResults()->string('word', ' ');

        $this->assertEquals('音楽 を 聴きます。', $string);
    }

    /**
     * @test
     */
    public function it_can_build_a_string_divided_by_nonspace_character(): void
    {
        $string = $this->getResults()->string('word', '|');

        $this->assertEquals('音楽|を|聴きます|。', $string);
    }

    /**
     * @test
     */
    public function it_can_get_all_words(): void
    {
        $words = $this->getResults()->words()->all();

        $this->assertEquals(['音楽', 'を', '聴きます', '。'], $words);
    }

    /**
     * @test
     */
    public function it_can_get_all_lemmas(): void
    {
        $lemmas = $this->getResults()->lemmas()->all();

        $this->assertEquals(['音楽', 'を', '聴く', '。'], $lemmas);
    }

    /**
     * @test
     */
    public function it_can_get_all_readings(): void
    {
        $readings = $this->getResults()->readings()->all();

        $this->assertEquals(['オンガク', 'ヲ', 'キキマス', '。'], $readings);
    }

    /**
     * @test
     */
    public function it_can_get_all_pronunciations(): void
    {
        $pronunciations = $this->getResults()->pronunciations()->all();

        $this->assertEquals(['オンガク', 'ヲ', 'キキマス', '。'], $pronunciations);
    }

    /**
     * @test
     */
    public function it_can_get_all_parts_of_speech(): void
    {
        $partsOfSpeech = $this->getResults()->partsOfSpeech()->all();

        $this->assertEquals(['noun', 'postposition', 'verb', 'symbol'], $partsOfSpeech);
    }

    /**
     * @test
     */
    public function it_can_get_romaji(): void
    {
        $romaji = $this->getResults()->romaji();

        $this->assertEquals(['ongaku', 'o', 'kikimasu', '.'], $romaji->all());
    }

    /**
     * @test
     */
    public function it_can_build_a_romaji_string_with_dividing_space(): void
    {
        $string = $this->getResults()->string('romaji', ' ');

        $this->assertEquals('ongaku o kikimasu.', $string);
    }

    /**
     * @test
     */
    public function it_can_build_a_romaji_string_with_dividing_char(): void
    {
        $string = $this->getResults()->string('romaji', '-');

        $this->assertEquals('ongaku-o-kikimasu-.', $string);
    }

    /**
     * @test
     */
    public function it_can_get_furigana(): void
    {
        $furigana = $this->getResults()->furigana();

        $this->assertEquals([
            '<ruby><rb>音楽</rb><rp>(</rp><rt>おんがく</rt><rp>)</rp></ruby>',
            'を',
            '<ruby><rb>聴</rb><rp>(</rp><rt>き</rt><rp>)</rp></ruby>きます',
            '。',
        ], $furigana->all());
    }

    /**
     * @test
     */
    public function it_can_build_a_furigana_string(): void
    {
        $string = $this->getResults()->string('furigana');

        $this->assertEquals('<ruby><rb>音楽</rb><rp>(</rp><rt>おんがく</rt><rp>)</rp></ruby>を<ruby><rb>聴</rb><rp>(</rp><rt>き</rt><rp>)</rp></ruby>きます。', $string);
    }

    /**
     * @test
     */
    public function it_can_convert_to_hiragana(): void
    {
        $results = $this->getResults()->toHiragana()->readings();

        $this->assertEquals(['おんがく', 'を', 'ききます', '。'], $results->all());
    }

    /**
     * @test
     */
    public function it_can_build_a_hiragana_string_with_spaces(): void
    {
        $string = $this->getResults()->toHiragana()->string('reading', ' ');

        $this->assertEquals('おんがく を ききます。', $string);
    }

    /**
     * @test
     */
    public function it_can_build_a_hiragana_string_with_dividing_char(): void
    {
        $string = $this->getResults()->toHiragana()->string('reading', '-');

        $this->assertEquals('おんがく-を-ききます-。', $string);
    }

    /**
     * @test
     */
    public function it_can_convert_to_katakana(): void
    {
        $string = $this->getResults()->toKatakana()->string('word');

        $this->assertEquals('音楽ヲ聴キマス。', $string);
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
            $this->getResults()->romaji();
        } finally {
            $config->resetConfig();
        }
    }

    /**
     * @test
     */
    public function it_can_get_plugin_data(): void
    {
        $furigana = $this->getResults()->plugin('Furigana');

        $this->assertEquals(
            '<ruby><rb>音楽</rb><rp>(</rp><rt>おんがく</rt><rp>)</rp></ruby>を<ruby><rb>聴</rb><rp>(</rp><rt>き</rt><rp>)</rp></ruby>きます。',
            $furigana);
    }

    /**
     * @test
     */
    public function it_puts_a_space_before_symbol_when_partofspeech(): void
    {
        $string = $this->getResults()->string('partOfSpeech', ' ');

        $this->assertEquals('noun postposition verb symbol', $string);
    }

    /**
     * @test
     */
    public function it_accepts_plural_string_values(): void
    {
        $string = $this->getResults()->string('words');

        $this->assertEquals('音楽を聴きます。', $string);
    }

    /**
     * @test
     */
    public function string_with_glue_does_not_start_with_glue(): void
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
