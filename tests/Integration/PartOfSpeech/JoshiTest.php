<?php

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class JoshiTest extends TestCase
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
     * @test
     */
    public function it_changes_part_of_speech_to_postposition()
    {
        $results = self::$limelight->parse('を');

        $this->assertEquals('postposition', $results->findIndex(0)->partOfSpeech());
    }

    /**
     * @test
     */
    public function it_attaches_setsuzokujoshi_to_previous_word()
    {
        $results = self::$limelight->parse('行けば');

        $this->assertEquals('行けば', $results->words());

        $words = $results->all();

        $this->assertCount(1, $words);
    }
}
