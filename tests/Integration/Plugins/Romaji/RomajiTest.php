<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\Plugins\Romaji;

use Limelight\Tests\TestCase;

class RomajiTest extends TestCase
{
    public function testItStoresSpaceSeperatedStringsOnObject(): void
    {
        $results = self::$limelight->parse('今週末山を登ります！');

        $conversion = $results->plugin('Romaji');

        $this->assertEquals('Konshūmatsu yama o noborimasu!', $conversion);
    }

    public function testItConvertsMultibyteCharsToUppercase(): void
    {
        $results = self::$limelight->parse('大阪');

        $conversion = $results->plugin('Romaji');

        $this->assertEquals('Ōsaka', $conversion);
    }

    public function testItAllowsEnglishPunctuationToRemainWhenNoparse(): void
    {
        $results = self::$limelight->noParse('うれ.しい');

        $conversion = $results->plugin('Romaji');

        $this->assertEquals('Ure.shii', $conversion);
    }

    public function testItPassesEnglishWords(): void
    {
        $results = self::$limelight->parse('大阪 pass 今週');

        $conversion = $results->plugin('Romaji');

        $this->assertEquals('Ōsaka pass konshū', $conversion);
    }
}
