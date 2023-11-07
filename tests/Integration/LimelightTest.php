<?php

declare(strict_types=1);

namespace Limelight\tests\Integration;

use Limelight\Limelight;
use Limelight\Config\Config;
use Limelight\Tests\TestCase;

class LimelightTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_instantiated(): void
    {
        $limelight = new Limelight();

        $this->assertInstanceOf(Limelight::class, $limelight);
    }

    /**
     * @test
     */
    public function it_can_parse_input(): void
    {
        $results = self::$limelight->parse('出来るかな。。。');

        $this->assertEquals('出来るかな。。。', $results->string('word'));
    }

    /**
     * @test
     */
    public function it_can_access_mecab_parseToNode_method(): void
    {
        $nodes = self::$limelight->mecabToNode('大丈夫');

        $expected = [
            'BOS/EOS,*,*,*,*,*,*,*,*',
            '名詞,形容動詞語幹,*,*,*,*,大丈夫,ダイジョウブ,ダイジョーブ',
            'BOS/EOS,*,*,*,*,*,*,*,*',
        ];

        $this->assertNodeResult($nodes, $expected);
    }

    /**
     * @test
     */
    public function it_can_access_mecab_parseToMecabNode_method(): void
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

    /**
     * @test
     */
    public function it_can_access_mecab_parseToString_method(): void
    {
        $results = self::$limelight->mecabToString('美味しい');

        $this->assertStringContainsString('形容詞,自立,*,*,形容詞・イ段,基本形,美味しい,オイシイ,オイシイ', $results);
    }

    /**
     * @test
     */
    public function it_can_set_config_values(): void
    {
        $limelight = self::$limelight;

        $limelight->setConfig('test', 'Romaji', 'style');

        $config = Config::getInstance();

        $romaji = $config->get('Romaji');

        $this->assertContains('test', $romaji);

        $config->resetConfig();
    }

    /**
     * @test
     */
    public function it_fires_event_after_word_is_created(): void
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

    /**
     * @test
     */
    public function it_fires_event_after_results_object_is_created(): void
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

    /**
     * @test
     */
    public function it_fires_event_after_word_is_created_in_noparse(): void
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

    /**
     * @test
     */
    public function it_fires_event_that_sets_new_listener_after_results_object_is_created_in_noparse(): void
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

    /**
     * @test
     */
    public function it_doesnt_fires_event_when_supressed_in_noparse(): void
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

    /**
     * @test
     */
    public function it_turns_events_back_on_after_running_noparse_with_event_supression(): void
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
