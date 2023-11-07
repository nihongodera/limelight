<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class JoshiTest extends TestCase
{
    /**
     * @test
     */
    public function it_changes_part_of_speech_to_postposition(): void
    {
        $results = self::$limelight->parse('を');

        $this->assertEquals('postposition', $results->pull(0)->partOfSpeech());
    }

    /**
     * @test
     */
    public function it_attaches_setsuzokujoshi_to_previous_word(): void
    {
        $results = self::$limelight->parse('行けば');

        $this->assertEquals('行けば', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);
    }
}
