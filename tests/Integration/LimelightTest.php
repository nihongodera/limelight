<?php

namespace Limelight\tests\Integration;

use Limelight\Limelight;
use Limelight\Config\Config;
use Limelight\Tests\TestCase;

class LimelightTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    protected static $limelight;

    /**
     * Set static limelight on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();
    }

    /**
     * @test
     */
    public function it_can_be_instantiated()
    {
        $limelight = new Limelight();

        $this->assertInstanceOf('Limelight\Limelight', $limelight);
    }

    /**
     * @test
     */
    public function it_can_parse_input()
    {
        $results = self::$limelight->parse('出来るかな。。。');

        $this->assertEquals('出来るかな。。。', $results->words());
    }

    /**
     * @test
     */
    public function it_can_access_mecab_parseToNode_method()
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
    public function it_can_access_mecab_parseToMecabNode_method()
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

            $count += 1;
        }
    }

    /**
     * @test
     */
    public function it_can_access_mecab_parseToString_method()
    {
        $results = self::$limelight->mecabToString('美味しい');

        $this->assertContains('形容詞,自立,*,*,形容詞・イ段,基本形,美味しい,オイシイ,オイシイ', $results);
    }

    /**
     * @test
     */
    public function it_can_access_mecab_split_method()
    {
        $results = self::$limelight->mecabSplit('めっちゃ眠い。');

        $this->assertEquals('めっちゃ', $results[0]);

        $this->assertEquals('眠い', $results[1]);

        $this->assertEquals('。', $results[2]);
    }

    /**
     * @test
     */
    public function it_can_set_config_values()
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
    public function it_fires_event_that_resets_config_after_word_is_created()
    {
        $config = Config::getInstance();

        $config->set(['Limelight\Tests\Stubs\TestListener'], 'listeners', 'WordWasCreated');

        $this->assertEquals('Limelight\Tests\Stubs\TestListener', $config->get('listeners')['WordWasCreated'][0]);

        $limelight = new Limelight();

        $results = $limelight->parse('出来るかな。。。');

        $this->assertEquals([], $config->get('listeners')['WordWasCreated']);

        $config->resetConfig();
    }

    /**
     * @test
     */
    public function it_fires_event_that_sets_new_listener_after_results_object_is_created()
    {
        $config = Config::getInstance();

        $config->set(['Limelight\Tests\Stubs\TestListener'], 'listeners', 'ParseWasSuccessful');

        $this->assertEquals('Limelight\Tests\Stubs\TestListener', $config->get('listeners')['ParseWasSuccessful'][0]);

        $limelight = new Limelight();

        $results = $limelight->parse('出来るかな。。。');

        $listeners = $config->get('listeners');

        $this->assertEquals([], $listeners['ParseWasSuccessful']);

        $this->assertEquals('Limelight\Tests\Stubs\TestListener', $listeners['WordWasCreated'][0]);

        $config->resetConfig();
    }

    /**
     * @test
     */
    public function it_fires_event_that_resets_config_after_word_is_created_in_noparse()
    {
        $config = Config::getInstance();

        $config->set(['Limelight\Tests\Stubs\TestListener'], 'listeners', 'WordWasCreated');

        $this->assertEquals('Limelight\Tests\Stubs\TestListener', $config->get('listeners')['WordWasCreated'][0]);

        $limelight = new Limelight();

        $results = $limelight->noParse('できるかな。。。');

        $this->assertEquals([], $config->get('listeners')['WordWasCreated']);

        $config->resetConfig();
    }

    /**
     * @test
     */
    public function it_fires_event_that_sets_new_listener_after_results_object_is_created_in_noparse()
    {
        $config = Config::getInstance();

        $config->set(['Limelight\Tests\Stubs\TestListener'], 'listeners', 'ParseWasSuccessful');

        $this->assertEquals('Limelight\Tests\Stubs\TestListener', $config->get('listeners')['ParseWasSuccessful'][0]);

        $limelight = new Limelight();

        $results = $limelight->noParse('できるかな。。。');

        $listeners = $config->get('listeners');

        $this->assertEquals([], $listeners['ParseWasSuccessful']);

        $this->assertEquals('Limelight\Tests\Stubs\TestListener', $listeners['WordWasCreated'][0]);
        
        $config->resetConfig();
    }

    /**
     * @test
     */
    public function it_doesnt_fires_event_when_supressed_in_noparse()
    {
        $config = Config::getInstance();

        $config->set(['Limelight\Tests\Stubs\TestListener'], 'listeners', 'WordWasCreated');

        $this->assertEquals('Limelight\Tests\Stubs\TestListener', $config->get('listeners')['WordWasCreated'][0]);

        $limelight = new Limelight();

        $results = $limelight->noParse('できるかな。。。', ['Romaji'], true);

        $this->assertEquals(['Limelight\Tests\Stubs\TestListener'], $config->get('listeners')['WordWasCreated']);

        $config->resetConfig();
    }

    /**
     * @test
     */
    public function it_turns_events_back_on_after_running_noparse_with_event_supression()
    {
        $config = Config::getInstance();

        $config->set(['Limelight\Tests\Stubs\TestListener'], 'listeners', 'WordWasCreated');

        $this->assertEquals('Limelight\Tests\Stubs\TestListener', $config->get('listeners')['WordWasCreated'][0]);

        $limelight = new Limelight();

        $results = $limelight->noParse('できるかな。。。', ['Romaji'], true);

        $this->assertEquals(['Limelight\Tests\Stubs\TestListener'], $config->get('listeners')['WordWasCreated']);

        $results = $limelight->parse('出来るかな。。。');

        $this->assertEquals([], $config->get('listeners')['WordWasCreated']);

        $config->resetConfig();
    }
}
