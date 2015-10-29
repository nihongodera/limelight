<?php

namespace Limelight\Tests\Classes;

use Limelight\Limelight;
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
     * Limelight.php can be instantiated.
     *
     * @test
     */
    public function it_can_be_instantiated()
    {
        $limelight = new Limelight();

        $this->assertInstanceOf('Limelight\Limelight', $limelight);
    }

    /**
     * Limelight.php can access mecab parseToNode().
     *
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
     * Limelight.php can access mecab parseToMecabNode().
     * 
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

        $this->assertNodeResult($rawNodes, $expected);
    }

    /**
     * Limelight.php can access mecab parseToString().
     *
     * @test
     */
    public function it_can_access_mecab_parseToString_method()
    {
        $results = self::$limelight->mecabToString('美味しい');

        $this->assertContains('形容詞,自立,*,*,形容詞・イ段,基本形,美味しい,オイシイ,オイシイ', $results);
    }

    /**
     * Limelight.php can access mecab split().
     *
     * @test
     */
    public function it_can_access_mecab_split_method()
    {
        $results = self::$limelight->mecabSplit('めっちゃ眠い。');

        $this->assertEquals('めっちゃ', $results[0]);

        $this->assertEquals('眠い', $results[1]);

        $this->assertEquals('。', $results[2]);
    }
}
