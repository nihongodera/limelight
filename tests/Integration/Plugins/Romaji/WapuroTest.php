<?php

namespace Limelight\tests\Integration\Plugins\Romaji;

use Limelight\Tests\TestCase;
use Limelight\Plugins\Library\Romaji\Styles\Wapuro;

class WapuroTest extends TestCase
{
    /**
     * @var Limelight\Plugins\Library\Romaji\Styles\Wapuro
     */
    protected static $wapuro;

    /**
     * Set static wapuro on object.
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$wapuro = new Wapuro();
    }

    /**
     * @test
     */
    public function it_converts_simple_word_to_romaji()
    {
        $results = self::$limelight->parse('行きます');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('ikimasu', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_nn_word_to_romaji()
    {
        $results = self::$limelight->parse('参加');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('sanka', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_simple_double_vowel_word_to_romaji()
    {
        $results = self::$limelight->parse('お兄さん');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('oniisan', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_sho_to_romaji()
    {
        $results = self::$limelight->parse('初夏');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('shoka', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_long_o_to_romaji()
    {
        $results = self::$limelight->parse('証券');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('shouken', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_kyou_to_romaji()
    {
        $results = self::$limelight->parse('今日');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('kyou', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_multiple_words_to_romaji()
    {
        $results = self::$limelight->parse('福岡に住んでいます。');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('Fukuokanisundeimasu.', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_double_k_to_romaji()
    {
        $results = self::$limelight->parse('結果');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('kekka', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_double_c_to_romaji()
    {
        $results = self::$limelight->parse('抹茶');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('maccha', $conversion);
    }

    /**
     * @test
     */
    public function it_doesnt_convert_nm()
    {
        $results = self::$limelight->parse('群馬');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('Gunma', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_n_vowel_to_nn()
    {
        $results = self::$limelight->parse('簡易');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('kanni', $conversion);
    }

    /**
     * @test
     */
    public function it_doesnt_convert_long_vowels_not_listed()
    {
        $results = self::$limelight->parse('お婆さん');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('obaasan', $conversion);
    }

    /**
     * @test
     */
    public function it_doesnt_convert_ha_to_wa()
    {
        $results = self::$limelight->parse('は');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('ha', $conversion);
    }

    /**
     * @test
     */
    public function it_doesnt_convert_he_to_e()
    {
        $results = self::$limelight->parse('へ');

        $conversion = $this->getRomajiConversion(self::$wapuro, $results);

        $this->assertEquals('he', $conversion);
    }
}
