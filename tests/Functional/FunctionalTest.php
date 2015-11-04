<?php

namespace Limelight\Tests\Functional;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class FunctionalTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    protected static $limelight;

    /**
     * @var array
     */
    protected static $lib;

    /**
     * Set static limelight and test libs on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();

        self::$lib = include 'tests/lib.php';
    }

    /**
     * It correctly parses a simple sentence.
     * 
     * @test
     */
    public function it_parses_a_simple_sentence()
    {
        $results = self::$limelight->parse('音楽を聴きます。');

        $this->assertEquals('音楽を聴きます。', $results->words());

        $words = $results->all();

        $this->assertCount(4, $words);

        $this->assertEquals('音楽', $words[0]->word());

        $this->assertEquals('noun', $words[0]->partOfSpeech());

        $this->assertEquals('を', $words[1]->word());

        $this->assertEquals('postposition', $words[1]->partOfSpeech());

        $this->assertEquals('聴きます', $words[2]->word());

        $this->assertEquals('verb', $words[2]->partOfSpeech());

        $this->assertEquals('。', $words[3]->word());

        $this->assertEquals('symbol', $words[3]->partOfSpeech());
    }

    /**
     * It parses a slightly more complicated sentence.
     * 
     * @test
     */
    public function it_parses_a_slightly_more_complicated_sentence()
    {
        $results = self::$limelight->parse('東京に行って、パスタを食べてしまった。');

        $this->assertEquals('東京に行って、パスタを食べてしまった。', $results->words());

        $words = $results->all();

        $this->assertCount(8, $words);

        $this->assertEquals('東京', $words[0]->word());

        $this->assertEquals('proper noun', $words[0]->partOfSpeech());

        $this->assertEquals('に', $words[1]->word());

        $this->assertEquals('postposition', $words[1]->partOfSpeech());

        $this->assertEquals('行って', $words[2]->word());

        $this->assertEquals('verb', $words[2]->partOfSpeech());

        $this->assertEquals('、', $words[3]->word());

        $this->assertEquals('symbol', $words[3]->partOfSpeech());

        $this->assertEquals('パスタ', $words[4]->word());

        $this->assertEquals('noun', $words[4]->partOfSpeech());

        $this->assertEquals('を', $words[5]->word());

        $this->assertEquals('postposition', $words[5]->partOfSpeech());

        $this->assertEquals('食べてしまった', $words[6]->word());

        $this->assertEquals('verb', $words[6]->partOfSpeech());

        $this->assertEquals('。', $words[7]->word());

        $this->assertEquals('symbol', $words[7]->partOfSpeech());
    }

    /**
     * It parses multiple sentences.
     * 
     * @test
     */
    public function it_parses_multiple_sentences()
    {
        $results = self::$limelight->parse('私はすき焼きが大好きです。だから、いつも食べています。');

        $this->assertEquals('私はすき焼きが大好きです。だから、いつも食べています。', $results->words());

        $words = $results->all();

        $this->assertCount(12, $words);
    }

    /**
     * Random character input does not break it.
     * 
     * @test
     */
    public function it_handles_random_characters()
    {
        $results = self::$limelight->parse('フキイldksf塩jkdfllsdf帰依kdサブ');

        $this->assertEquals('フキイldksf塩jkdfllsdf帰依kdサブ', $results->words());

        $words = $results->all();
    }

    /**
     * It parses text.
     * 
     * @test
     */
    public function it_parses_text_1()
    {
         $results = self::$limelight->parse(self::$lib['textOne']);

         $this->assertEquals(preg_replace('/\s+/', '', self::$lib['textOne']), $results->words());

         $words = $results->all();

         $this->assertCount(445, $words);
    }
}