<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\Mecab;

use Limelight\Mecab\Mecab;
use Limelight\Tests\TestCase;
use Limelight\Mecab\PhpMecab\PhpMecab;

class PhpMecabNodeTest extends TestCase
{
    protected static Mecab $phpmecab;

    public static function setUpBeforeClass(): void
    {
        self::$phpmecab = new PhpMecab([]);
    }

    /**
     * @test
     */
    public function it_can_get_the_next_node(): void
    {
        $node = self::$phpmecab->parseToNode('眠たいです。');

        $this->assertEquals('BOS/EOS,*,*,*,*,*,*,*,*', $node->getFeature());

        $node = $node->getNext();

        $this->assertEquals('形容詞,自立,*,*,形容詞・アウオ段,基本形,眠たい,ネムタイ,ネムタイ', $node->getFeature());
    }

    /**
     * @test
     */
    public function it_can_get_the_feature(): void
    {
        $node = self::$phpmecab->parseToNode('眠たいです。');

        $this->assertEquals('BOS/EOS,*,*,*,*,*,*,*,*', $node->getFeature());
    }

    /**
     * @test
     */
    public function it_can_get_the_surface(): void
    {
        $node = self::$phpmecab->parseToNode('眠たいです。');

        $node = $node->getNext();

        $this->assertEquals('眠たい', $node->getSurface());
    }
}
