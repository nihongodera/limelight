<?php

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class SetsuzokushiTest extends TestCase
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
    public function it_changes_partOfSpeech_to_conjunction()
    {
        $results = self::$limelight->parse('けれども');

        $this->assertEquals('conjunction', $results->findIndex(0)->partOfSpeech());
    }
}