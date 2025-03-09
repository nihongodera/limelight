<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\Plugins\Romaji;

use Limelight\Plugins\Library\Romaji\Styles\HepburnTraditional;
use Limelight\Tests\TestCase;

class HepburnTraditionalTest extends TestCase
{
    protected static HepburnTraditional $hepburn;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$hepburn = new HepburnTraditional();
    }

    public function testItConvertsSimpleWordToRomaji(): void
    {
        $results = self::$limelight->parse('行きます');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('ikimasu', $conversion);
    }

    public function testItConvertsNnWordToRomaji(): void
    {
        $results = self::$limelight->parse('参加');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('sanka', $conversion);
    }

    public function testItConvertsSimpleDoubleVowelWordToRomaji(): void
    {
        $results = self::$limelight->parse('お兄さん');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('oniisan', $conversion);
    }

    public function testItConvertsShoToRomaji(): void
    {
        $results = self::$limelight->parse('初夏');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('shoka', $conversion);
    }

    public function testItConvertsLongOToRomaji(): void
    {
        $results = self::$limelight->parse('証券');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('shōken', $conversion);
    }

    public function testItConvertsKyouToRomaji(): void
    {
        $results = self::$limelight->parse('今日');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('kyō', $conversion);
    }

    public function testItConvertsMultipleWordsToRomaji(): void
    {
        $results = self::$limelight->parse('福岡に住んでいます。');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('Fukuokanisundeimasu.', $conversion);
    }

    public function testItConvertsDoubleKToRomaji(): void
    {
        $results = self::$limelight->parse('結果');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('kekka', $conversion);
    }

    public function testItConvertsDoubleCToRomaji(): void
    {
        $results = self::$limelight->parse('抹茶');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('matcha', $conversion);
    }

    public function testItConvertsNmToMm(): void
    {
        $results = self::$limelight->parse('群馬');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('Gumma', $conversion);
    }

    public function testItConvertsNVowelToNDash(): void
    {
        $results = self::$limelight->parse('簡易');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('kan-i', $conversion);
    }

    public function testItDoesntConvertLongVowelsNotListed(): void
    {
        $results = self::$limelight->parse('お婆さん');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('obaasan', $conversion);
    }

    public function testItConvertsHaToWa(): void
    {
        $results = self::$limelight->parse('は');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('wa', $conversion);
    }

    public function testItConvertsHeToE(): void
    {
        $results = self::$limelight->parse('へ');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('e', $conversion);
    }
}
