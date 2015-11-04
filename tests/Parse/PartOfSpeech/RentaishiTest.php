<?php

namespace Limelight\Tests\Parse\PartOfSpeech;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class RentaishiTest extends TestCase
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
     * Class changes the part of speech to 'determiner'.
     * 
     * @test
     */
    public function it_changes_partOfSpeech_to_determiner()
    {
        $results = self::$limelight->parse('いわゆる');

        $this->assertEquals('determiner', $results->findIndex(0)->partOfSpeech());
    }
}