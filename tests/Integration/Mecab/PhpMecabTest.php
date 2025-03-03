<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\Mecab;

use Limelight\Mecab\Mecab;
use Limelight\Mecab\PhpMecab\PhpMecab;
use Limelight\Tests\TestCase;

class PhpMecabTest extends TestCase
{
    protected static Mecab $phpmecab;

    public static function setUpBeforeClass(): void
    {
        self::$phpmecab = new PhpMecab([]);
    }

    public function testItCanBeInstantiated(): void
    {
        $phpmecab = new PhpMecab([]);

        $this->assertInstanceOf(PhpMecab::class, $phpmecab);
    }

    public function testItCanPerformMecabParseToNodeMethod(): void
    {
        $nodes = self::$phpmecab->parseToNode('大丈夫');

        $expected = [
            'BOS/EOS,*,*,*,*,*,*,*,*',
            '名詞,形容動詞語幹,*,*,*,*,大丈夫,ダイジョウブ,ダイジョーブ',
            'BOS/EOS,*,*,*,*,*,*,*,*',
        ];

        $this->assertNodeResult($nodes, $expected);
    }

    public function testItCanAccessMecabParseToStringMethod(): void
    {
        $results = self::$phpmecab->parseToString('美味しい');

        $this->assertStringContainsString('形容詞,自立,*,*,形容詞・イ段,基本形,美味しい,オイシイ,オイシイ', $results);
    }
}
