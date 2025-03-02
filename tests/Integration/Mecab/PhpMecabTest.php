<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\Mecab;

use Limelight\Mecab\Mecab;
use Limelight\Tests\TestCase;
use Limelight\Mecab\PhpMecab\PhpMecab;

class PhpMecabTest extends TestCase
{
    protected static Mecab $phpmecab;

    public static function setUpBeforeClass(): void
    {
        self::$phpmecab = new PhpMecab([]);
    }

    /**
     * @test
     */
    public function it_can_be_instantiated(): void
    {
        $phpmecab = new PhpMecab([]);

        $this->assertInstanceOf(PhpMecab::class, $phpmecab);
    }

    /**
     * @test
     */
    public function it_can_perform_mecab_parseToNode_method(): void
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
    public function it_can_access_mecab_parseToString_method(): void
    {
        $results = self::$phpmecab->parseToString('美味しい');

        $this->assertStringContainsString('形容詞,自立,*,*,形容詞・イ段,基本形,美味しい,オイシイ,オイシイ', $results);
    }
}
