<?php

namespace Limelight\tests\Integration\Plugins\Romaji;

use Limelight\Tests\TestCase;

class RomajiTest extends TestCase
{
    /**
     * @test
     */
    public function it_stores_space_seperated_strings_on_object()
    {
        $results = self::$limelight->parse('今週末山を登ります！');

        $conversion = $results->plugin('Romaji');

        $this->assertEquals('Konshūmatsu yama o noborimasu!', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_multibyte_chars_to_uppercase()
    {
        $results = self::$limelight->parse('大阪');

        $conversion = $results->plugin('Romaji');

        $this->assertEquals('Ōsaka', $conversion);
    }

    /**
     * @test
     */
    public function it_allows_english_punctuation_to_remain_when_noparse()
    {
        $results = self::$limelight->noParse('うれ.しい');

        $conversion = $results->plugin('Romaji');

        $this->assertEquals('Ure.shii', $conversion);
    }

    /**
     * @test
     */
    public function it_passes_english_words()
    {
        $results = self::$limelight->parse('大阪 pass 今週');

        $conversion = $results->plugin('Romaji');

        $this->assertEquals('Ōsaka pass konshū', $conversion);
    }
}
