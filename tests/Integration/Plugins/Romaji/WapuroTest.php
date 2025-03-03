<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\Plugins\Romaji;

use Limelight\Plugins\Library\Romaji\Styles\Wapuro;
use Limelight\Tests\TestCase;

class WapuroTest extends TestCase
{
    protected static Wapuro $wapuro;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$wapuro = new Wapuro();
    }

    public function testItConvertsSimpleWordToRomaji(): void
    {
        $results = self::$limelight->parse('行きます');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('ikimasu', $conversion);
    }

    public function testItConvertsNnWordToRomaji(): void
    {
        $results = self::$limelight->parse('参加');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('sanka', $conversion);
    }

    public function testItConvertsSimpleDoubleVowelWordToRomaji(): void
    {
        $results = self::$limelight->parse('お兄さん');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('oniisan', $conversion);
    }

    public function testItConvertsShoToRomaji(): void
    {
        $results = self::$limelight->parse('初夏');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('shoka', $conversion);
    }

    public function testItConvertsLongOToRomaji(): void
    {
        $results = self::$limelight->parse('証券');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('shouken', $conversion);
    }

    public function testItConvertsKyouToRomaji(): void
    {
        $results = self::$limelight->parse('今日');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('kyou', $conversion);
    }

    public function testItConvertsMultipleWordsToRomaji(): void
    {
        $results = self::$limelight->parse('福岡に住んでいます。');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('Fukuokanisundeimasu.', $conversion);
    }

    public function testItConvertsDoubleKToRomaji(): void
    {
        $results = self::$limelight->parse('結果');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('kekka', $conversion);
    }

    public function testItConvertsDoubleCToRomaji(): void
    {
        $results = self::$limelight->parse('抹茶');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('maccha', $conversion);
    }

    public function testItDoesntConvertNm(): void
    {
        $results = self::$limelight->parse('群馬');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('Gunma', $conversion);
    }

    public function testItConvertsNVowelToNn(): void
    {
        $results = self::$limelight->parse('簡易');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('kanni', $conversion);
    }

    public function testItDoesntConvertLongVowelsNotListed(): void
    {
        $results = self::$limelight->parse('お婆さん');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('obaasan', $conversion);
    }

    public function testItDoesntConvertHaToWa(): void
    {
        $results = self::$limelight->parse('は');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('ha', $conversion);
    }

    public function testItDoesntConvertHeToE(): void
    {
        $results = self::$limelight->parse('へ');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('he', $conversion);
    }
}
