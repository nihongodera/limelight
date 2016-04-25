<?php

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class JodoushiTest extends TestCase
{
    /**
     * @test
     */
    public function it_changes_partOfSpeech_to_postposition()
    {
        $results = self::$limelight->parse('です');

        $this->assertEquals('postposition', $results->pull(0)->partOfSpeech());
    }

    /**
     * @test
     */
    public function it_doesnt_attach_desu_to_previous_word()
    {
        $results = self::$limelight->parse('大好きです');

        $words = $results->all();

        $this->assertCount(2, $words);

        $this->assertEquals('大好きです', $results->string('word'));

        $this->assertEquals('です', $words[1]->word());
    }

    /**
     * @test
     */
    public function it_attaches_desired_inflections_to_previous_word()
    {
        $results = self::$limelight->parse('したくない');

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('したくない', $results->string('word'));
    }

    /**
     * @test
     */
    public function it_attaches_nn_to_previous_word()
    {
        $results = self::$limelight->parse('見えません');

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('見えません', $results->string('word'));
    }

    /**
     * @test
     */
    public function it_attaches_u_to_previous_word()
    {
        $results = self::$limelight->parse('作ろう');

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('作ろう', $results->string('word'));
    }

    /**
     * @test
     */
    public function it_changes_partOfSpeech_to_verb_when_da()
    {
        $results = self::$limelight->parse('楽しいだ');

        $this->assertEquals('verb', $results->pull(1)->partOfSpeech());
    }

    /**
     * @test
     */
    public function it_changes_partOfSpeech_to_verb_when_desu()
    {
        $results = self::$limelight->parse('美味しいです');

        $this->assertEquals('verb', $results->pull(1)->partOfSpeech());
    }
}
