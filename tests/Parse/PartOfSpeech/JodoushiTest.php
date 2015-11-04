<?php

namespace Limelight\Tests\Parse\PartOfSpeech;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class JodoushiTest extends TestCase
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
     * It changes partOfSpeech to 'postposition'.
     * 
     * @test
     */
    public function it_changes_partOfSpeech_to_postposition()
    {
        $results = self::$limelight->parse('です');

        $this->assertEquals('postposition', $results->findIndex(0)->partOfSpeech());
    }

    /**
     * It doesn't attach 'desu' to previous.
     * 
     * @test
     */
    public function it_doesnt_attach_desu_to_previous()
    {
        $results = self::$limelight->parse('大好きです');

        $words = $results->all();

        $this->assertCount(2, $words);

        $this->assertEquals('大好きです', $results->words());

        $this->assertEquals('です', $words[1]->word());
    }

    /**
     * It attaches certain inflections to previous.
     * 
     * @test
     */
    public function it_attaches_desired_inflections_to_previous()
    {
        $results = self::$limelight->parse('したくない');

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('したくない', $results->words());
    }

    /**
     * It attaches certain nn to previous.
     * 
     * @test
     */
    public function it_attaches_nn_to_previous()
    {
        $results = self::$limelight->parse('見えません');

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('見えません', $results->words());
    }

    /**
     * It attaches certain u to previous.
     * 
     * @test
     */
    public function it_attaches_u_to_previous()
    {
        $results = self::$limelight->parse('作ろう');

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('作ろう', $results->words());
    }

    /**
     * It changes partOfSpeech to 'verb' when da.
     * 
     * @test
     */
    public function it_changes_partOfSpeech_to_verb_when_da()
    {
        $results = self::$limelight->parse('楽しいだ');

        $this->assertEquals('verb', $results->findIndex(1)->partOfSpeech());
    }

    /**
     * It changes partOfSpeech to 'verb' when desu.
     * 
     * @test
     */
    public function it_changes_partOfSpeech_to_verb_when_desu()
    {
        $results = self::$limelight->parse('美味しいです');

        $this->assertEquals('verb', $results->findIndex(1)->partOfSpeech());
    }
}
