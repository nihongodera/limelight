<?php

namespace Limelight\Tests\Parse\PartOfSpeech;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class SettoushiTest extends TestCase
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
     * Class changes the part of speech to 'prefix'.
     * 
     * @test
     */
    public function it_changes_partOfSpeech_to_prefix()
    {
        $results = self::$limelight->parse('超音速');

        $this->assertEquals('prefix', $results->findIndex(0)->partOfSpeech());
    }
}