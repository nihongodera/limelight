<?php

namespace Limelight\tests\Classes;

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
     * It can be instantiated.
     *
     * @test
     */
    public function it_can_be_instantiated()
    {
        $results = new LimelightResults('test', ['item', 'another thing'], []);

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $results);
    }

    /**
     * Calling class as function invokes generator.
     *
     * @test
     */
    public function it_invokes_generator_when_called_as_function()
    {
        $results = self::$results;

        foreach ($results() as $result) {
            $this->assertInstanceOf('Limelight\Classes\LimelightWord', $result);
        }
    }

    /**
     * It prints info when printed or echoed.
     *
     * @test
     */
    public function it_prints_info_when_printed()
    {
        $results = self::$results;

        ob_start();

        echo $results;

        $output = ob_get_contents();

        ob_end_clean();

        $this->assertContains('音楽', $output);
    }

    /**
     * It can get the all words array off the object.
     *
     * @test
     */
    public function it_can_get_all_words()
    {
        $words = self::$results->all();

        $this->AssertCount(4, $words);
    }

    /**
     * It can get the original text off the object.
     *
     * @test
     */
    public function it_can_get_original_string()
    {
        $original = self::$results->original();

        $this->AssertEquals('音楽を聴きます。', $original);
    }

    /**
     * It can make a result string from words.
     *
     * @test
     */
    public function it_can_build_words_string()
    {
        $string = self::$results->words();

        $this->AssertEquals('音楽を聴きます。', $string);
    }

    /**
     * It can make a result string from words with spaces.
     *
     * @test
     */
    public function it_can_build_words_string_with_spaces()
    {
        $string = self::$results->words(true);

        $this->AssertEquals('音楽 を 聴きます。', $string);
    }

    /**
     * It can make a result string from words with dividing char.
     *
     * @test
     */
    public function it_can_build_words_string_with_dividing_char()
    {
        $string = self::$results->words(true, '-');

        $this->AssertEquals('音楽-を-聴きます-。', $string);
    }

    /**
     * It can make a lemma string from words.
     *
     * @test
     */
    public function it_can_build_lemma_string()
    {
        $string = self::$results->lemmas();

        $this->AssertEquals('音楽を聴く。', $string);
    }

    /**
     * It can make a lemma string from words.
     *
     * @test
     */
    public function it_can_build_reading_string()
    {
        $string = self::$results->readings();

        $this->AssertEquals('オンガクヲキキマス。', $string);
    }

    /**
     * It can make a lemma string from words.
     *
     * @test
     */
    public function it_can_build_pronunciation_string()
    {
        $string = self::$results->pronunciations();

        $this->AssertEquals('オンガクヲキキマス。', $string);
    }

    /**
     * It can make a lemma string from words.
     *
     * @test
     */
    public function it_can_build_partsOfSpeech_string()
    {
        $string = self::$results->partsOfSpeech();

        $this->AssertEquals('noun postposition verb symbol', $string);
    }

    /**
     * It can convert to hiragana.
     *
     * @test
     */
    public function it_can_convert_to_hiragana()
    {
        $string = self::$results->toHiragana()->readings();

        $this->AssertEquals('おんがくをききます。', $string);
    }

    /**
     * It can make a hiragana string from words with spaces.
     *
     * @test
     */
    public function it_can_build_hiragana_string_with_spaces()
    {
        $string = self::$results->toHiragana()->words(true);

        $this->AssertEquals('おんがく を ききます。', $string);
    }

    /**
     * It can make a hiragana string from words with dividing char.
     *
     * @test
     */
    public function it_can_build_hiragana_string_with_dividing_char()
    {
        $string = self::$results->toHiragana()->words(true, '-');

        $this->AssertEquals('おんがく-を-ききます-。', $string);
    }

    /**
     * It can convert to katakana.
     *
     * @test
     */
    public function it_can_convert_to_katakana()
    {
        $string = self::$results->toKatakana()->lemmas();

        $this->AssertEquals('オンガクヲキク。', $string);
    }

    /**
     * It can convert to romanji.
     *
     * @test
     */
    public function it_can_convert_to_romanji()
    {
        $string = self::$results->toRomanji()->words();

        $this->AssertEquals('Ongaku o kikimasu.', $string);
    }

    /**
     * It can make a romanji string from words with spaces.
     *
     * @test
     */
    public function it_can_build_romanji_string_with_spaces()
    {
        $string = self::$results->toRomanji()->words(true);

        $this->AssertEquals('Ongaku o kikimasu.', $string);
    }

    /**
     * It can make a romanji string from words with dividing char.
     *
     * @test
     */
    public function it_can_build_romanji_string_with_dividing_char()
    {
        $string = self::$results->toRomanji()->words(true, '-');

        $this->AssertEquals('Ongaku-o-kikimasu.', $string);
    }

    /**
     * It can convert to furigana.
     *
     * @test
     */
    public function it_can_convert_to_furigana()
    {
        $string = self::$results->toFurigana()->lemmas();

        $this->AssertEquals('<ruby>音楽<rt>おんがく</rt></ruby>を<ruby>聴<rt>き</rt></ruby>く。', $string);
    }

    /**
     * It throws exception when plugin not registered.
     *
     * @test
     * @expectedException Limelight\Exceptions\PluginNotFoundException
     * @expectedExceptionMessage Plugin Romanji not found in config.php
     */
    public function it_throws_exception_when_plugin_not_registered()
    {
        $config = Config::getInstance();

        $config->erase('plugins', 'Romanji');

        $string = self::$results->toRomanji()->words();
    }

    /**
     * It can get words from generator.
     *
     * @test
     */
    public function it_can_get_words_from_generator()
    {
        $results = self::$results;

        $words = $results->all();

        $count = 0;

        foreach ($results->next() as $word) {
            $this->AssertEquals($words[$count], $word);

            $count += 1;
        }
    }

    /**
     * It can get a single word by string.
     *
     * @test
     */
    public function it_can_get_word_by_string()
    {
        $word = self::$results->findWord('聴きます');

        $this->AssertEquals('聴きます', $word->word());
    }

    /**
     * It throws exception when word is not present.
     *
     * @test
     * @expectedException Limelight\Exceptions\InvalidInputException
     * @expectedExceptionMessage Word 佐賀県 does not exist.
     */
    public function it_throws_exception_for_invalid_string()
    {
        $word = self::$results->findWord('佐賀県');
    }

    /**
     * It can get a single word by index.
     *
     * @test
     */
    public function it_can_get_word_by_index()
    {
        $word = self::$results->findIndex(2);

        $this->AssertEquals('聴きます', $word->word());
    }

    /**
     * It throws exception when index is not present.
     *
     * @test
     * @expectedException Limelight\Exceptions\InvalidInputException
     * @expectedExceptionMessage Index 23 does not exist. Results contain exactly 4 item(s).
     */
    public function it_throws_exception_for_invalid_index()
    {
        $word = self::$results->findIndex(23);
    }

    /**
     * It can get plugin data.
     *
     * @test
     */
    public function it_can_get_plugin_data()
    {
        $furigana = self::$results->plugin('Furigana');

        $this->AssertEquals('<ruby>音楽<rt>おんがく</rt></ruby>を<ruby>聴<rt>き</rt></ruby>きます。', $furigana);
    }
}
