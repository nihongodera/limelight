<?php

declare(strict_types=1);

namespace Limelight\tests\Integration;

use Limelight\Config\Config;
use Limelight\Limelight;
use Limelight\Tests\TestCase;

class LimelightTest extends TestCase
{
    public function testItCanBeInstantiated(): void
    {
        $limelight = new Limelight();

        $this->assertInstanceOf(Limelight::class, $limelight);
    }

    public function testItCanParseInput(): void
    {
        $results = self::$limelight->parse('出来るかな。。。');

        $this->assertEquals('出来るかな。。。', $results->string('word'));
    }

    public function testItCanAccessMecabParseToNodeMethod(): void
    {
        $nodes = self::$limelight->mecabToNode('大丈夫');

        $expected = [
            'BOS/EOS,*,*,*,*,*,*,*,*',
            '名詞,形容動詞語幹,*,*,*,*,大丈夫,ダイジョウブ,ダイジョーブ',
            'BOS/EOS,*,*,*,*,*,*,*,*',
        ];

        $this->assertNodeResult($nodes, $expected);
    }

    public function testItCanAccessMecabParseToMecabNodeMethod(): void
    {
        $rawNodes = self::$limelight->mecabToMecabNode('大丈夫');

        $expected = [
            'BOS/EOS,*,*,*,*,*,*,*,*',
            '名詞,形容動詞語幹,*,*,*,*,大丈夫,ダイジョウブ,ダイジョーブ',
            'BOS/EOS,*,*,*,*,*,*,*,*',
        ];

        $count = 0;

        foreach ($rawNodes as $node) {
            $expectedLine = $expected[$count];

            $this->assertEquals($expectedLine, $node->feature);

            $count++;
        }
    }

    public function testItCanAccessMecabParseToStringMethod(): void
    {
        $results = self::$limelight->mecabToString('美味しい');

        $this->assertStringContainsString('形容詞,自立,*,*,形容詞・イ段,基本形,美味しい,オイシイ,オイシイ', $results);
    }

    public function testItCanSetConfigValues(): void
    {
        $limelight = self::$limelight;

        $limelight->setConfig('test', 'Romaji', 'style');

        $config = Config::getInstance();

        $romaji = $config->get('Romaji');

        $this->assertContains('test', $romaji);

        $config->resetConfig();
    }

    public function testItFiresEventAfterWordIsCreated(): void
    {
        $this->clearLog();

        $limelight = new Limelight();

        $limelight->dispatcher()->addListeners(['Limelight\Tests\Stubs\TestListener'], 'WordWasCreated');

        $limelight->parse('出来るかな。');

        $log = $this->readLog();

        $this->assertEquals('WordWasCreated fired. 出来るWordWasCreated fired. かWordWasCreated fired. なWordWasCreated fired. 。', $log);

        $this->clearLog();

        $limelight->dispatcher()->clearListeners();
    }

    public function testItFiresEventAfterResultsObjectIsCreated(): void
    {
        $this->clearLog();

        $limelight = new Limelight();

        $limelight->dispatcher()->addListeners(['Limelight\Tests\Stubs\TestListener'], 'ParseWasSuccessful');

        $limelight->parse('出来るかな。');

        $log = $this->readLog();

        $this->assertEquals('ParseWasSuccessful fired.出来るかな。', $log);

        $this->clearLog();

        $limelight->dispatcher()->clearListeners();
    }

    public function testItFiresEventAfterWordIsCreatedInNoParse(): void
    {
        $this->clearLog();

        $limelight = new Limelight();

        $limelight->dispatcher()->addListeners(['Limelight\Tests\Stubs\TestListener'], 'WordWasCreated');

        $limelight->noParse('できるかな。');

        $log = $this->readLog();

        $this->assertEquals('WordWasCreated fired. できるかな。', $log);

        $this->clearLog();

        $limelight->dispatcher()->clearListeners();
    }

    public function testItFiresEventThatSetsNewListenerAfterResultsObjectIsCreatedInNoParse(): void
    {
        $this->clearLog();

        $limelight = new Limelight();

        $limelight->dispatcher()->addListeners(['Limelight\Tests\Stubs\TestListener'], 'ParseWasSuccessful');

        $limelight->noParse('できるかな。');

        $log = $this->readLog();

        $this->assertEquals('ParseWasSuccessful fired.できるかな。', $log);

        $this->clearLog();

        $limelight->dispatcher()->clearListeners();
    }

    public function testItDoesntFiresEventWhenSuppressedInNoParse(): void
    {
        $this->clearLog();

        $limelight = new Limelight();

        $limelight->dispatcher()->addListeners(['Limelight\Tests\Stubs\TestListener'], 'WordWasCreated');

        $limelight->noParse('できるかな。', ['Romaji'], true);

        $log = $this->readLog();

        $this->assertEquals('', $log);

        $this->clearLog();

        $limelight->dispatcher()->clearListeners();
    }

    public function testItTurnsEventsBackOnAfterRunningNoParseWithEventSuppression(): void
    {
        $this->clearLog();

        $limelight = new Limelight();

        $limelight->dispatcher()->addListeners(['Limelight\Tests\Stubs\TestListener'], 'WordWasCreated');

        $limelight->noParse('できるかな。。。', ['Romaji'], true);

        $log = $this->readLog();

        $this->assertEquals('', $log);

        $limelight->parse('出来るかな。');

        $log = $this->readLog();

        $this->assertEquals('WordWasCreated fired. 出来るWordWasCreated fired. かWordWasCreated fired. なWordWasCreated fired. 。', $log);

        $this->clearLog();

        $limelight->dispatcher()->clearListeners();
    }
}
