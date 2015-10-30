<?php

namespace Limelight\Tests\Classes;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class RomanjiTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    private static $limelight;

    /**
     * Set static hepburn on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();
    }

    /**
     * Strings stored on LimelightResults are space seperated.
     * 
     * @test
     */
    public function it_stores_space_seperated_strings_on_object()
    {
        $results = self::$limelight->parse('今週末山を登ります！');

        $conversion = $results->plugin('Romanji');

        $this->assertEquals('Konshuumatsu yama wo noborimasu！', $conversion);
    }
}
