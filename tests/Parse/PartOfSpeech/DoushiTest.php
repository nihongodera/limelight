<?php

namespace Limelight\Tests\Parse\PartOfSpeech;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class DoushiTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    private static $limelight;

    /**
     * Set Limelight on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();
    }

    /**
     * Class changes the part of speech to 'verb'.
     * 
     * @test
     */
    public function it_changes_partOfSpeech_to_verb()
    {
        $results = self::$limelight->parse('開く');

        $this->assertEquals('verb', $results->findIndex(0)->partOfSpeech());
    }

    /**
     * It attaches setsubi to previous word.
     * 
     * @test
     */
    public function it_attaches_setsubi_to_previous()
    {
        $results = self::$limelight->parse('乗せられる');

        $this->assertEquals('verb', $results->findIndex(0)->partOfSpeech());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('乗せられる', $words[0]->word());
    }

    /**
     * It attaches hijiritsu to previous word.
     * 
     * @test
     */
    public function it_attaches_hijiritsu_to_previous()
    {
        $results = self::$limelight->parse('開いてる');

        $this->assertEquals('verb', $results->findIndex(0)->partOfSpeech());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('開いてる', $words[0]->word());
    }

    /**
     * It parses a simple verb.
     * 
     * @test
     */
    public function it_parses_a_simple_verb()
    {
        $results = self::$limelight->parse('行く');

        $this->assertEquals('行く', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('行く', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    /**
     * It parses a verb conjugated like 行きます.
     * 
     * @test
     */
    public function it_parses_a_masu_verb()
    {
        $results = self::$limelight->parse('帰ります');

        $this->assertEquals('帰ります', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('帰ります', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    /**
     * It parses a verb conjugated like 行きました.
     * 
     * @test
     */
    public function it_parses_a_mashita_verb()
    {
        $results = self::$limelight->parse('読みました');

        $this->assertEquals('読みました', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('読みました', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    /**
     * It parses a verb conjugated like 行った.
     * 
     * @test
     */
    public function it_parses_a_ta_verb()
    {
        $results = self::$limelight->parse('始まった');

        $this->assertEquals('始まった', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('始まった', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    /**
     * It parses a verb conjugated like 行かない.
     * 
     * @test
     */
    public function it_parses_a_nai_verb()
    {
        $results = self::$limelight->parse('干さない');

        $this->assertEquals('干さない', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('干さない', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    /**
     * It parses a verb conjugated like 行きません.
     * 
     * @test
     */
    public function it_parses_a_masen_verb()
    {
        $results = self::$limelight->parse('掛けません');

        $this->assertEquals('掛けません', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('掛けません', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    /**
     * It parses a verb conjugated like 行かなかった.
     * 
     * @test
     */
    public function it_parses_a_nakatta_verb()
    {
        $results = self::$limelight->parse('落とさなかった');

        $this->assertEquals('落とさなかった', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('落とさなかった', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    /**
     * It parses a verb conjugated like 行っていた.
     * 
     * @test
     */
    public function it_parses_a_teita_verb()
    {
        $results = self::$limelight->parse('聞いていた');

        $this->assertEquals('聞いていた', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('聞いていた', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    /**
     * It parses a verb conjugated like 行ってた.
     * 
     * @test
     */
    public function it_parses_a_teta_verb()
    {
        $results = self::$limelight->parse('取ってた');

        $this->assertEquals('取ってた', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('取ってた', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    /**
     * It parses a verb conjugated like 行っている.
     * 
     * @test
     */
    public function it_parses_a_teiru_verb()
    {
        $results = self::$limelight->parse('見ている');

        $this->assertEquals('見ている', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('見ている', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    /**
     * It parses a verb conjugated like 行ってる.
     * 
     * @test
     */
    public function it_parses_a_teru_verb()
    {
        $results = self::$limelight->parse('飲んでる');

        $this->assertEquals('飲んでる', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('飲んでる', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    /**
     * It parses a verb conjugated like 行け.
     * 
     * @test
     */
    public function it_parses_an_e_verb()
    {
        $results = self::$limelight->parse('行け');

        $this->assertEquals('行け', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('行け', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    /**
     * It parses a verb conjugated like 行けば.
     * 
     * @test
     */
    public function it_parses_an_eba_verb()
    {
        $results = self::$limelight->parse('買えば');

        $this->assertEquals('買えば', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('買えば', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    /**
     * It parses a verb conjegated like 行ったら.
     *
     * @test
     */
    public function it_parses_a_tara_verb()
    {
        $results = self::$limelight->parse('書いたら');

        $this->assertEquals('書いたら', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('書いたら', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    /**
     * It parses a verb conjegated like 行こう.
     *
     * @test
     */
    public function it_parses_an_ou_verb()
    {
        $results = self::$limelight->parse('しよう');

        $this->assertEquals('しよう', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('しよう', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    /**
     * It parses a verb conjegated like 行ける.
     *
     * @test
     */
    public function it_parses_an_eru_verb()
    {
        $results = self::$limelight->parse('載せれる');

        $this->assertEquals('載せれる', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('載せれる', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }
}
