<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class MeishiTest extends TestCase
{
    public function testItChangesPartOfSpeechToNoun(): void
    {
        $results = self::$limelight->parse('テレビ');

        $this->assertEquals('noun', $results->pull(0)->partOfSpeech());
    }

    public function testItHandlesProperNouns(): void
    {
        $results = self::$limelight->parse('東京');

        $this->assertEquals('東京', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('東京', $words[0]->word());

        $this->assertEquals('proper noun', $words[0]->partOfSpeech());
    }

    public function testItHandlesPronouns(): void
    {
        $results = self::$limelight->parse('私');

        $this->assertEquals('私', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('私', $words[0]->word());

        $this->assertEquals('pronoun', $words[0]->partOfSpeech());
    }

    public function testItHandlesSahensuruVerbs(): void
    {
        $results = self::$limelight->parse('全うするために');

        $this->assertEquals('全うするために', $results->string('word'));

        $words = $results->all();

        $this->assertEquals('全うする', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    public function testItHandlesTokushudaAdjectives(): void
    {
        $results = self::$limelight->parse('大好きです');

        $this->assertEquals('大好きです', $results->string('word'));

        $words = $results->all();

        $this->assertEquals('大好き', $words[0]->word());

        $this->assertEquals('adjective', $words[0]->partOfSpeech());
    }

    public function testItHandlesFukushikanouPlusJoshi(): void
    {
        $results = self::$limelight->parse('食べるために');

        $this->assertEquals('食べるために', $results->string('word'));

        $words = $results->all();

        $this->assertCount(2, $words);

        $this->assertEquals('ために', $words[1]->word());

        $this->assertEquals('adverb', $words[1]->partOfSpeech());
    }

    public function testItHandlesKanjiNumbers(): void
    {
        $results = self::$limelight->parse('一');

        $this->assertEquals('一', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('一', $words[0]->word());

        $this->assertEquals('number', $words[0]->partOfSpeech());
    }

    public function testItHandlesRomanNumbers(): void
    {
        $results = self::$limelight->parse('5');

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('5', $words[0]->word());

        $this->assertEquals('number', $words[0]->partOfSpeech());
    }
}
