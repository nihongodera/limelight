<?php

namespace Limelight\Tests\PartOfSpeech;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class FukushiTest extends TestCase
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
     * Class changes the part of speech to 'adverb'.
     * 
     * @test
     */
    public function it_changes_partOfSpeech_to_adverb()
    {
        $results = self::$limelight->parse('ときどき');

        $this->assertEquals('adverb', $results->getByIndex(0)->partOfSpeech()->get());
    }
}
