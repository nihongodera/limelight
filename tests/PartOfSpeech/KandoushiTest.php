<?php

namespace Limelight\Tests\PartOfSpeech;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class KandoushiTest extends TestCase
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
     * Class changes the part of speech to 'interjection'.
     * 
     * @test
     */
    public function it_changes_partOfSpeech_to_interjection()
    {
        $results = self::$limelight->parse('あれ');

        $this->assertEquals('interjection', $results->getByIndex(0)->partOfSpeech()->get());
    }
}
