<?php

namespace Limelight\tests\Integration;

use Limelight\Limelight;
use Limelight\Config\Config;
use Limelight\Tests\TestCase;
use Limelight\Classes\LimelightResults;

class LimelightResultsTest extends TestCase
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

        self::$results = self::$limelight->parse('音楽を聴きます。');
    }

    /**
     * @test
     */
    public function it_can_be_instantiated()
    {
        $results = new LimelightResults('test', ['item', 'another thing'], []);

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $results);
    }

    /**
     * @test
     */
    public function it_can_be_iterated_over()
    {
        $results = self::$results;

        foreach ($results as $result) {
            $this->assertInstanceOf('Limelight\Classes\LimelightWord', $result);
        }
    }

    /**
     * @test
     */
    public function it_prints_info_when_object_printed()
    {
        $results = self::$results;

        ob_start();

        echo $results;

        $output = ob_get_contents();

        ob_end_clean();

        $this->assertContains('音楽', $output);
    }

    /**
     * @test
     */
    public function it_can_get_all_limelightword_objects()
    {
        $words = self::$results->all();

        $this->AssertCount(4, $words);
    }

    /**
     * @test
     */
    public function it_can_get_original_input_string()
    {
        $original = self::$results->original();

        $this->assertEquals('音楽を聴きます。', $original);
    }

    /**
     * @test
     */
    public function it_can_build_a_string()
    {
        $string = self::$results->string('word');

        $this->assertEquals('音楽を聴きます。', $string);
    }

    /**
     * @test
     */
    public function it_can_build_a_string_divided_by_spaces()
    {
        $string = self::$results->string('word', ' ');

        $this->assertEquals('音楽 を 聴きます。', $string);
    }

    /**
     * @test
     */
    public function it_can_build_a_string_divided_by_mb_spaces()
    {
        $string = self::$results->string('word', ' ');

        $this->assertEquals('音楽 を 聴きます。', $string);
    }

    /**
     * @test
     */
    public function it_can_build_a_string_divided_by_nonspace_character()
    {
        $string = self::$results->string('word', '|');

        $this->assertEquals('音楽|を|聴きます|。', $string);
    }

    /**
     * @test
     */
    public function it_can_get_all_words()
    {
        $words = self::$results->words()->all();

        $this->assertEquals(['音楽', 'を', '聴きます', '。'], $words);
    }

    /**
     * @test
     */
    public function it_can_get_all_lemmas()
    {
        $lemmas = self::$results->lemmas()->all();

        $this->assertEquals(['音楽', 'を', '聴く', '。'], $lemmas);
    }

    /**
     * @test
     */
    public function it_can_get_all_readings()
    {
        $readings = self::$results->readings()->all();

        $this->assertEquals(['オンガク', 'ヲ', 'キキマス', '。'], $readings);
    }

    /**
     * @test
     */
    public function it_can_get_all_pronunciations()
    {
        $pronunciations = self::$results->pronunciations()->all();

        $this->assertEquals(['オンガク', 'ヲ', 'キキマス', '。'], $pronunciations);
    }

    /**
     * @test
     */
    public function it_can_get_all_parts_of_speech()
    {
        $partsOfSpeech = self::$results->partsOfSpeech()->all();

        $this->assertEquals(['noun', 'postposition', 'verb', 'symbol'], $partsOfSpeech);
    }

    // /**
    //  * @test
    //  */
    // public function it_can_convert_to_hiragana()
    // {
    //     $string = self::$results->toHiragana()->readings();

    //     $this->assertEquals('おんがくをききます。', $string);
    // }

    // /**
    //  * @test
    //  */
    // public function it_can_build_a_hiragana_string_with_spaces()
    // {
    //     $string = self::$results->toHiragana()->words(true);

    //     $this->assertEquals('おんがく を ききます。', $string);
    // }

    // /**
    //  * @test
    //  */
    // public function it_can_build_a_hiragana_string_with_dividing_char()
    // {
    //     $string = self::$results->toHiragana()->words(true, '-');

    //     $this->assertEquals('おんがく-を-ききます-。', $string);
    // }

    // /**
    //  * @test
    //  */
    // public function it_can_convert_to_katakana()
    // {
    //     $string = self::$results->toKatakana()->lemmas();

    //     $this->assertEquals('オンガクヲキク。', $string);
    // }

    // /**
    //  * @test
    //  */
    // public function it_can_convert_to_romaji()
    // {
    //     $string = self::$results->toRomaji()->words();

    //     $this->assertEquals('Ongaku o kikimasu.', $string);
    // }

    // *
    //  * @test
     
    // public function it_can_build_a_romaji_string_with_spaces()
    // {
    //     $string = self::$results->toRomaji()->words(true);

    //     $this->assertEquals('Ongaku o kikimasu.', $string);
    // }

    // /**
    //  * @test
    //  */
    // public function it_can_build_a_romaji_string_with_dividing_char()
    // {
    //     $string = self::$results->toRomaji()->words(true, '-');

    //     $this->assertEquals('Ongaku-o-kikimasu.', $string);
    // }

    // /**
    //  * @test
    //  */
    // public function it_can_convert_to_furigana()
    // {
    //     $string = self::$results->toFurigana()->lemmas();

    //     $this->assertEquals('<ruby><rb>音楽</rb><rp>(</rp><rt>おんがく</rt><rp>)</rp></ruby>を<ruby><rb>聴</rb><rp>(</rp><rt>き</rt><rp>)</rp></ruby>く。', $string);
    // }

    // /**
    //  * @test
    //  * @expectedException Limelight\Exceptions\PluginNotFoundException
    //  * @expectedExceptionMessage Plugin Romaji not found in config.php
    //  */
    // public function it_throws_exception_when_plugin_not_registered()
    // {
    //     $config = Config::getInstance();

    //     $config->erase('plugins', 'Romaji');

    //     $string = self::$results->toRomaji()->words();
    // }

    // /**
    //  * @test
    //  */
    // public function it_can_get_words_from_generator_method()
    // {
    //     $results = self::$results;

    //     $words = $results->all();

    //     $count = 0;

    //     foreach ($results->next() as $word) {
    //         $this->assertEquals($words[$count], $word);

    //         $count += 1;
    //     }
    // }

    // /**
    //  * @test
    //  */
    // public function it_can_get_a_word_by_string()
    // {
    //     $word = self::$results->findWord('聴きます');

    //     $this->assertEquals('聴きます', $word->word());
    // }

    // /**
    //  * @test
    //  * @expectedException Limelight\Exceptions\InvalidInputException
    //  * @expectedExceptionMessage Word 佐賀県 does not exist.
    //  */
    // public function it_throws_exception_for_an_invalid_string()
    // {
    //     $word = self::$results->findWord('佐賀県');
    // }

    // /**
    //  * @test
    //  */
    // public function it_can_get_a_word_by_index()
    // {
    //     $word = self::$results->findIndex(2);

    //     $this->assertEquals('聴きます', $word->word());
    // }

    // /**
    //  * @test
    //  * @expectedException Limelight\Exceptions\InvalidInputException
    //  * @expectedExceptionMessage Index 23 does not exist. Results contain exactly 4 item(s).
    //  */
    // public function it_throws_exception_for_an_invalid_index()
    // {
    //     $word = self::$results->findIndex(23);
    // }

    // /**
    //  * @test
    //  */
    // public function it_can_get_plugin_data()
    // {
    //     $furigana = self::$results->plugin('Furigana');

    //     $this->assertEquals('<ruby><rb>音楽</rb><rp>(</rp><rt>おんがく</rt><rp>)</rp></ruby>を<ruby><rb>聴</rb><rp>(</rp><rt>き</rt><rp>)</rp></ruby>きます。', $furigana);
    // }
}
