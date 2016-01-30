<?php

namespace Limelight\tests\Integration\Plugins\Romanji;

use Limelight\Limelight;
use Limelight\Tests\TestCase;
use Limelight\Plugins\Library\Romanji\Styles\NihonShiki;

class NihonShikiTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    private static $limelight;

    /**
     * @var Limelight\Plugins\Library\Romanji\Styles\NihonShiki
     */
    protected static $nihon;

    /**
     * Set static hepburn on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();

        self::$nihon = new NihonShiki();
    }

    /**
     * @test
     */
    public function it_converts_simple_word_to_romanji()
    {
        $results = self::$limelight->parse('行きます');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('ikimasu', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_nn_word_to_romanji()
    {
        $results = self::$limelight->parse('参加');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('sanka', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_simple_double_vowel_word_to_romanji()
    {
        $results = self::$limelight->parse('お兄さん');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('oniisan', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_sho_to_romanji()
    {
        $results = self::$limelight->parse('初夏');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('syoka', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_long_o_to_romanji()
    {
        $results = self::$limelight->parse('証券');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('syôken', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_kyou_to_romanji()
    {
        $results = self::$limelight->parse('今日');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('kyô', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_multiple_words_to_romanji()
    {
        $results = self::$limelight->parse('福岡に住んでいます。');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('Hukuokanisundeimasu.', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_double_k_to_romanji()
    {
        $results = self::$limelight->parse('結果');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('kekka', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_double_c_to_romanji()
    {
        $results = self::$limelight->parse('抹茶');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('mattya', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_nm_to_mm()
    {
        $results = self::$limelight->parse('群馬');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('Gunma', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_n_vowel_to_n_dash()
    {
        $results = self::$limelight->parse('簡易');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('kan\'i', $conversion);
    }

    /**
     * @test
     */
    public function it_doesnt_convert_long_vowels_not_listed()
    {
        $results = self::$limelight->parse('お婆さん');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('obâsan', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_ha_to_wa()
    {
        $results = self::$limelight->parse('は');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('ha', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_he_to_e()
    {
        $results = self::$limelight->parse('へ');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('he', $conversion);
    }
}
