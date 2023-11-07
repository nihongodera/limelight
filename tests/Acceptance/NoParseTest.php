<?php

declare(strict_types=1);

namespace Limelight\tests\Acceptance;

use Limelight\Tests\TestCase;
use Limelight\Exceptions\InvalidInputException;
use Limelight\Exceptions\PluginNotFoundException;

class NoParseTest extends TestCase
{
    /**
     * @test
     */
    public function it_parses_kana_text(): void
    {
        $results = self::$limelight->noParse('できるかな。。。');

        $this->assertEquals('できるかな。。。', $results->string('word'));
    }

    /**
     * @test
     */
    public function it_gets_romaji_for_kana_text(): void
    {
        $results = self::$limelight->noParse('ねんがっぴ');

        $this->assertEquals('nengappi', $results->string('romaji'));
    }

    /**
     * @test
     */
    public function it_doesnt_run_plugins_not_in_given_whitelist(): void
    {
        $this->expectExceptionMessage(
            'Plugin data for Romaji can not be found. Is the Romaji plugin registered in config?'
        );
        $this->expectException(PluginNotFoundException::class);

        $results = self::$limelight->noParse('ねんがっぴ', ['Furigana']);

        $results->plugin('romaji');
    }

    /**
     * @test
     */
    public function it_throws_exception_for_kanji_text(): void
    {
        $this->expectExceptionMessage('Text must not contain kanji.');
        $this->expectException(InvalidInputException::class);

        self::$limelight->noParse('今日');
    }
}
