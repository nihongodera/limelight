<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class JodoushiTest extends TestCase
{
    public function testItChangesPartOfSpeechToPostposition(): void
    {
        $results = self::$limelight->parse('です');

        $this->assertEquals('postposition', $results->pull(0)->partOfSpeech());
    }

    public function testItDoesntAttachDesuToPreviousWord(): void
    {
        $results = self::$limelight->parse('大好きです');

        $words = $results->all();

        $this->assertCount(2, $words);

        $this->assertEquals('大好きです', $results->string('word'));

        $this->assertEquals('です', $words[1]->word());
    }

    public function testItAttachesDesiredInflectionsToPreviousWord(): void
    {
        $results = self::$limelight->parse('したくない');

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('したくない', $results->string('word'));
    }

    public function testItAttachesNnToPreviousWord(): void
    {
        $results = self::$limelight->parse('見えません');

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('見えません', $results->string('word'));
    }

    public function testItAttachesUToPreviousWord(): void
    {
        $results = self::$limelight->parse('作ろう');

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('作ろう', $results->string('word'));
    }

    public function testItChangesPartOfSpeechToVerbWhenDa(): void
    {
        $results = self::$limelight->parse('楽しいだ');

        $this->assertEquals('verb', $results->pull(1)->partOfSpeech());
    }

    public function testItChangesPartOfSpeechToVerbWhenDesu(): void
    {
        $results = self::$limelight->parse('美味しいです');

        $this->assertEquals('verb', $results->pull(1)->partOfSpeech());
    }
}
