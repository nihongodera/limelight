<?php

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class KeiyoushiTest extends TestCase
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
    public function it_changes_partOfSpeech_to_adjective()
    {
        $results = self::$limelight->parse('熱い');

        $this->assertEquals('adjective', $results->findIndex(0)->partOfSpeech());
    }
}