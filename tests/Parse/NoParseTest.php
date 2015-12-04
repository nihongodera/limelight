<?php

namespace Limelight\Tests\Classes;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class NoParseTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    protected static $limelight;

    /**
     * Set static limelight on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();
    }

    /**
     * It parses kana text.
     *
     * @test
     */
    public function it_parses_kana_text()
    {
        $results = self::$limelight->noParse('できるかな。。。');

        $this->assertEquals('できるかな。。。', $results->words());
    }

    /**
     * It gets romanji for kana text.
     *
     * @test
     */
    public function it_gets_romanji_for_kana_text()
    {
        $results = self::$limelight->noParse('ねんがっぴ');

        $this->assertEquals('Nengappi', $results->plugin('romanji'));
    }

    /**
     * It throws exception for kanji input.
     *
     * @test
     * @expectedException Limelight\Exceptions\InvalidInputException
     * @expectedExceptionMessage Text must not contain kanji.
     */
    public function it_throws_exception_for_kanji_text()
    {
        $results = self::$limelight->noParse('今日');
    }
}
