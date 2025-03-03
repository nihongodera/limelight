<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\Plugins\Romaji;

use Limelight\Plugins\Library\Romaji\Styles\HepburnModified;
use Limelight\Tests\TestCase;

class HepburnModifiedTest extends TestCase
{
    protected static HepburnModified $hepburn;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$hepburn = new HepburnModified();
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

    public function testItConvertsShouToRomaji(): void
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

    public function testItDoesntConvertDoubleAWhenSeperateWords(): void
    {
        $results = self::$limelight->parse('邪悪');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('jaaku', $conversion);
    }

    public function testItConvertsDoubleAWhenNotSeperateWords(): void
    {
        $results = self::$limelight->parse('お婆さん');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('obāsan', $conversion);
    }

    public function testItConvertsDoubleUWhenNotSeperateWords(): void
    {
        $results = self::$limelight->parse('数学');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('sūgaku', $conversion);
    }

    public function testItDoesntConvertDoubleUWhenSeperateWords(): void
    {
        $results = self::$limelight->parse('湖');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('mizuumi', $conversion);
    }

    public function testItDoesntConvertDoubleUOnVowels(): void
    {
        $results = self::$limelight->parse('食う');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('kuu', $conversion);
    }

    public function testItDoesntConvertDoubleI(): void
    {
        $results = self::$limelight->parse('お兄さん');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('oniisan', $conversion);
    }

    public function testItConvertsDoubleEWhenNotSeperateWords(): void
    {
        $results = self::$limelight->parse('お姉さん');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('onēsan', $conversion);
    }

    public function testItDoesntConvertDoubleEWhenSeperateWords(): void
    {
        $results = self::$limelight->parse('濡れ縁');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('nureen', $conversion);
    }

    public function testItConvertsDoubleOWhenNotSeperateWords(): void
    {
        $results = self::$limelight->parse('小躍り');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('koodori', $conversion);
    }

    public function testItDoesntConvertDoubleOWhenSeperateWords(): void
    {
        $results = self::$limelight->parse('氷');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('kōri', $conversion);
    }

    public function testItConvertsOuWhenNotSeperateWords(): void
    {
        $results = self::$limelight->parse('迷う');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('mayou', $conversion);
    }

    public function testItDoesntConvertOuWhenSeperateWords(): void
    {
        $results = self::$limelight->parse('学校');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('gakkō', $conversion);
    }

    public function testItDoesntConvertNm(): void
    {
        $results = self::$limelight->parse('群馬');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('Gunma', $conversion);
    }

    public function testItConvertsNVowelToNApostrophe(): void
    {
        $results = self::$limelight->parse('簡易');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('kan\'i', $conversion);
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

    public function testItConvertsWoToO(): void
    {
        $results = self::$limelight->parse('を');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('o', $conversion);
    }
}
