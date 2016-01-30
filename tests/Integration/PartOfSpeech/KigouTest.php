<?php

namespace Limelight\tests\Integration\PartOfSpeech;

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
     * @test
     */
    public function it_changes_partOfSpeech_to_symbol()
    {
        $results = self::$limelight->parse('。');

        $this->assertEquals('symbol', $results->findIndex(0)->partOfSpeech());
    }
}
