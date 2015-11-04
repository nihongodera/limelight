<?php

namespace Limelight\Tests\Parse\PartOfSpeech;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class KigouTest extends TestCase
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
     * Class changes the part of speech to 'symbol'.
     * 
     * @test
     */
    public function it_changes_partOfSpeech_to_symbol()
    {
        $results = self::$limelight->parse('。');

        $this->assertEquals('symbol', $results->findIndex(0)->partOfSpeech());
    }
}
