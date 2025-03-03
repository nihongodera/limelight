<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class DoushiTest extends TestCase
{
    public function testItChangesPartOfSpeechToVerb(): void
    {
        $results = self::$limelight->parse('開く');

        $this->assertEquals('verb', $results->pull(0)->partOfSpeech());
    }

    public function testItAttachesSetsubiToPreviousWord(): void
    {
        $results = self::$limelight->parse('乗せられる');

        $this->assertEquals('verb', $results->slice(0, 1)->first()->partOfSpeech());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('乗せられる', $words[0]->word());
    }

    public function testItAttachesHijiritsuToPreviousWord(): void
    {
        $results = self::$limelight->parse('開いてる');

        $this->assertEquals('verb', $results->slice(0, 1)->first()->partOfSpeech());

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('開いてる', $words[0]->word());
    }

    public function testItParsesASimpleVerb(): void
    {
        $results = self::$limelight->parse('行く');

        $this->assertEquals('行く', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('行く', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    public function testItParsesAMasuVerbLikeIkimasu(): void
    {
        $results = self::$limelight->parse('帰ります');

        $this->assertEquals('帰ります', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('帰ります', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    public function testItParsesAMashitaVerbLikeIkimashita(): void
    {
        $results = self::$limelight->parse('読みました');

        $this->assertEquals('読みました', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('読みました', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    public function testItParsesATaVerbLikeItta(): void
    {
        $results = self::$limelight->parse('始まった');

        $this->assertEquals('始まった', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('始まった', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    public function testItParsesANaiVerbLikeIkanai(): void
    {
        $results = self::$limelight->parse('干さない');

        $this->assertEquals('干さない', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('干さない', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    public function testItParsesAMasenVerbIkimasen(): void
    {
        $results = self::$limelight->parse('掛けません');

        $this->assertEquals('掛けません', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('掛けません', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    public function testItParsesANakattaVerbLikeIkanakatta(): void
    {
        $results = self::$limelight->parse('落とさなかった');

        $this->assertEquals('落とさなかった', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('落とさなかった', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    public function testItParsesATeitaVerbLikeItteita(): void
    {
        $results = self::$limelight->parse('聞いていた');

        $this->assertEquals('聞いていた', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('聞いていた', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    public function testItParsesATetaVerbLikeItteta(): void
    {
        $results = self::$limelight->parse('取ってた');

        $this->assertEquals('取ってた', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('取ってた', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    public function testItParsesATeiruVerbLikeItteiru(): void
    {
        $results = self::$limelight->parse('見ている');

        $this->assertEquals('見ている', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('見ている', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    public function testItParsesATeruVerbLikeItteru(): void
    {
        $results = self::$limelight->parse('飲んでる');

        $this->assertEquals('飲んでる', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('飲んでる', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    public function testItParsesAnEVerbLikeIke(): void
    {
        $results = self::$limelight->parse('行け');

        $this->assertEquals('行け', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('行け', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    public function testItParsesAnEbaVerbLikeIkeba(): void
    {
        $results = self::$limelight->parse('買えば');

        $this->assertEquals('買えば', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('買えば', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    public function testItParsesATaraVerbLikeIttara(): void
    {
        $results = self::$limelight->parse('書いたら');

        $this->assertEquals('書いたら', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('書いたら', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    public function testItParsesAnOuVerbLikeIkou(): void
    {
        $results = self::$limelight->parse('しよう');

        $this->assertEquals('しよう', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('しよう', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }

    public function testItParsesAnEruVerbLikeIkeru(): void
    {
        $results = self::$limelight->parse('載せれる');

        $this->assertEquals('載せれる', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);

        $this->assertEquals('載せれる', $words[0]->word());

        $this->assertEquals('verb', $words[0]->partOfSpeech());
    }
}
