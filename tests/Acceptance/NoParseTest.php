<?php

namespace Limelight\tests\Acceptance;

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
     * @test
     */
    public function it_parses_kana_text()
    {
        $results = self::$limelight->noParse('できるかな。。。');

        $this->assertEquals('できるかな。。。', $results->words());
    }

    /**
     * @test
     */
    public function it_gets_romanji_for_kana_text()
    {
        $results = self::$limelight->noParse('ねんがっぴ');

        $this->assertEquals('Nengappi', $results->plugin('romanji'));
    }

    /**
     * @test
     */
    public function it_doesnt_run_plugins_not_in_given_whitelist()
    {
        $results = self::$limelight->noParse('ねんがっぴ', ['Furigana']);

        $this->assertEquals('', $results->plugin('romanji'));
    }

    /**
     * @test
     */
    public function it_capitalizes_items_in_whitelist()
    {
        $results = self::$limelight->noParse('ねんがっぴ', ['furigana']);

        $this->assertEquals('', $results->plugin('romanji'));
    }

    /**
     * @test
     * @expectedException Limelight\Exceptions\InvalidInputException
     * @expectedExceptionMessage Text must not contain kanji.
     */
    public function it_throws_exception_for_kanji_text()
    {
        $results = self::$limelight->noParse('今日');
    }
}
