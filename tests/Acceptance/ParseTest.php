<?php

namespace Limelight\tests\Acceptance;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class ParseTest extends TestCase
{
    /**
     * @var array
     */
    protected static $lib;

    /**
     * Set static limelight and test libs on object.
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        
        self::$lib = include 'tests/lib.php';
    }

    /**
     * @test
     */
    public function it_parses_a_simple_sentence()
    {
        $results = self::$limelight->parse('音楽を聴きます。');

        $this->assertEquals('音楽を聴きます。', $results->string('word'));

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
     * @test
     */
    public function it_parses_a_slightly_more_complicated_sentence()
    {
        $results = self::$limelight->parse('東京に行って、パスタを食べてしまった。');

        $this->assertEquals('東京に行って、パスタを食べてしまった。', $results->string('word'));

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
     * @test
     */
    public function it_parses_multiple_sentences()
    {
        $results = self::$limelight->parse('私はすき焼きが大好きです。だから、いつも食べています。');

        $this->assertEquals('私はすき焼きが大好きです。だから、いつも食べています。', $results->string('word'));

        $words = $results->all();

        $this->assertCount(12, $words);
    }

    /**
     * @test
     */
    public function it_handles_random_characters()
    {
        $results = self::$limelight->parse('フキイldksf塩jkdfllsdf帰依kdサブ');

        $this->assertEquals('フキイldksf塩jkdfllsdf帰依kdサブ', $results->string('word'));

        $words = $results->all();
    }

    /**
     * @test
     */
    public function it_parses_text()
    {
         $results = self::$limelight->parse(self::$lib['textOne']);

         $this->assertEquals(preg_replace('/\s+/', '', self::$lib['textOne']), $results->string('word'));

         $words = $results->all();

         $this->assertCount(450, $words);
    }

    /**
     * @test
     */
    public function it_fires_single_event_when_word_is_not_parsed()
    {
        $this->clearLog();

        $limelight = new Limelight();

        $limelight->dispatcher()->addListeners(['Limelight\Tests\Stubs\TestListener'], 'WordWasCreated');

        $results = $limelight->parse('ケータイ');

        $log = $this->readLog();

        $this->assertEquals('WordWasCreated fired. ケータイ', $log);

        $this->clearLog();

        $limelight->dispatcher()->clearListeners();
    }
}