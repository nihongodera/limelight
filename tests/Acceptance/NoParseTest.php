<?php

declare(strict_types=1);

namespace Limelight\tests\Acceptance;

use Limelight\Exceptions\InvalidInputException;
use Limelight\Exceptions\PluginNotFoundException;
use Limelight\Tests\TestCase;

class NoParseTest extends TestCase
{
    public function testItParsesKanaText(): void
    {
        $results = self::$limelight->noParse('できるかな。。。');

        $this->assertEquals('できるかな。。。', $results->string('word'));
    }

    public function testItGetsRomajiForKanaText(): void
    {
        $results = self::$limelight->noParse('ねんがっぴ');

        $this->assertEquals('nengappi', $results->string('romaji'));
    }

    public function testItDoesntRunPluginsNotInGivenWhitelist(): void
    {
        $this->expectExceptionMessage(
            'Plugin data for Romaji can not be found. Is the Romaji plugin registered in config?'
        );
        $this->expectException(PluginNotFoundException::class);

        $results = self::$limelight->noParse('ねんがっぴ', ['Furigana']);

        $results->plugin('romaji');
    }

    public function testItThrowsExceptionForKanjiText(): void
    {
        $this->expectExceptionMessage('Text must not contain kanji.');
        $this->expectException(InvalidInputException::class);

        self::$limelight->noParse('今日');
    }
}
