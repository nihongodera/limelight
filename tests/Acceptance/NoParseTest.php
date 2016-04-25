<?php

namespace Limelight\tests\Acceptance;

use Limelight\Tests\TestCase;

class NoParseTest extends TestCase
{
    /**
     * @test
     */
    public function it_parses_kana_text()
    {
        $results = self::$limelight->noParse('できるかな。。。');

        $this->assertEquals('できるかな。。。', $results->string('word'));
    }

    /**
     * @test
     */
    public function it_gets_romaji_for_kana_text()
    {
        $results = self::$limelight->noParse('ねんがっぴ');

        $this->assertEquals('nengappi', $results->string('romaji'));
    }

    /**
     * @test
     *
     * @expectedException Limelight\Exceptions\PluginNotFoundException
     * @expectedExceptionMessage Plugin data for Romaji can not be found. Is the Romaji plugin registered in config?
     */
    public function it_doesnt_run_plugins_not_in_given_whitelist()
    {
        $results = self::$limelight->noParse('ねんがっぴ', ['Furigana']);

        $results->plugin('romaji');
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
