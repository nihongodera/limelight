<?php

namespace Limelight\tests\Integration\Mecab;

use Limelight\Tests\TestCase;
use Limelight\Mecab\PhpMecab\PhpMecab;

class PhpMecabTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    protected static $phpmecab;

    /**
     * Set static limelight on object.
     */
    public static function setUpBeforeClass()
    {
        self::$phpmecab = new PhpMecab([]);
    }

    /**
     * @test
     */
    public function it_can_be_instantiated()
    {
        $phpmecab = new PhpMecab([]);

        $this->assertInstanceOf('Limelight\Mecab\PhpMecab\PhpMecab', $phpmecab);
    }

    /**
     * @test
     */
    public function it_can_perform_mecab_parseToNode_method()
    {
        $nodes = self::$phpmecab->parseToNode('大丈夫');

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
    public function it_can_access_mecab_parseToString_method()
    {
        $results = self::$phpmecab->parseToString('美味しい');

        $this->assertContains('形容詞,自立,*,*,形容詞・イ段,基本形,美味しい,オイシイ,オイシイ', $results);
    }
}
