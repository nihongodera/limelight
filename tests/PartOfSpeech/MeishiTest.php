<?php

namespace Limelight\Tests\PartOfSpeech;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class MeishiTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    private static $limelight;

    /**
     * Set Limelight on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();
    }

    /**
     * Class changes the part of speech to 'noun'.
     * 
     * @test
     */
    public function it_changes_part_of_speech_to_noun()
    {
        $results = self::$limelight->parse('テレビ');

        $this->assertEquals('noun', $results->getByIndex(0)->partOfSpeech()->get());
    }

    /**
     * It handles proper nouns.
     * 
     * @test
     */
    public function it_handles_proper_nouns()
    {
        $results = self::$limelight->parse('東京');

        $this->assertEquals('東京', $results->getResultString());

        $words = $results->getAll();

        $this->assertCount(1, $words);

        $this->assertEquals('東京', $words[0]->word()->get());

        $this->assertEquals('proper noun', $words[0]->partOfSpeech()->get());
    }

    /**
     * It handles pronouns.
     * 
     * @test
     */
    public function it_handles_pronouns()
    {
        $results = self::$limelight->parse('私');

        $this->assertEquals('私', $results->getResultString());

        $words = $results->getAll();

        $this->assertCount(1, $words);

        $this->assertEquals('私', $words[0]->word()->get());

        $this->assertEquals('pronoun', $words[0]->partOfSpeech()->get());
    }

    /**
     * It handles sahensureu verbs.
     * 
     * @test
     */
    public function it_handles_sahensuru_verbs()
    {
        $results = self::$limelight->parse('全うするために');

        $this->assertEquals('全うするために', $results->getResultString());

        $words = $results->getAll();

        $this->assertEquals('全うする', $words[0]->word()->get());

        $this->assertEquals('verb', $words[0]->partOfSpeech()->get());
    }

    /**
     * It handles tokushuda adjectives.
     * 
     * @test
     */
    public function it_handles_tokushuda_adjectives()
    {
        $results = self::$limelight->parse('大好きです');

        $this->assertEquals('大好きです', $results->getResultString());

        $words = $results->getAll();

        $this->assertEquals('大好き', $words[0]->word()->get());

        $this->assertEquals('adjective', $words[0]->partOfSpeech()->get());
    }

    /**
     * It handles tokushuda adjectives.
     * 
     * @test
     */
    public function it_handles_fukushikanou_plus_joshi()
    {
        $results = self::$limelight->parse('食べるために');

        $this->assertEquals('食べるために', $results->getResultString());

        $words = $results->getAll();

        $this->assertCount(2, $words);

        $this->assertEquals('ために', $words[1]->word()->get());

        $this->assertEquals('adverb', $words[1]->partOfSpeech()->get());
    }

    /**
     * It handles kanji numbers.
     * 
     * @test
     */
    public function it_handles_kanji_numbers()
    {
        $results = self::$limelight->parse('一');

        $this->assertEquals('一', $results->getResultString());

        $words = $results->getAll();

        $this->assertCount(1, $words);

        $this->assertEquals('一', $words[0]->word()->get());

        $this->assertEquals('number', $words[0]->partOfSpeech()->get());
    }

    /**
     * It handles roman numbers.
     * 
     * @test
     */
    public function it_handles_roman_numbers()
    {
        $results = self::$limelight->parse('5');

        $words = $results->getAll();

        $this->assertCount(1, $words);

        $this->assertEquals('5', $words[0]->word()->get());

        $this->assertEquals('number', $words[0]->partOfSpeech()->get());
    }
}
