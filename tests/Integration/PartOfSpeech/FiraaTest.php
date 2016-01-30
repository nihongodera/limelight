<?php

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class FiraaTest extends TestCase
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
    public function it_sets_partOfSpeech_to_interjection()
    {
        $results = self::$limelight->parse('えーと');

        $this->assertEquals('interjection', $results->findIndex(0)->partOfSpeech());
    }
}
