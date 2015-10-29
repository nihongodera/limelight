<?php

namespace Limelight\Tests\PartOfSpeech;

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
     * Class changes the part of speech to 'conjunction'.
     * 
     * @test
     */
    public function it_changes_partOfSpeech_to_conjunction()
    {
        $results = self::$limelight->parse('けれども');

        $this->assertEquals('conjunction', $results->getByIndex(0)->partOfSpeech()->get());
    }
}