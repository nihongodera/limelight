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

        $limelight->setConfig('test', 'Romanji', 'style');

        $config = Config::getInstance();

        $romanji = $config->get('Romanji');

        $this->assertContains('test', $romanji);

        $config->resetConfig();
    }
}
