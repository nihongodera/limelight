<?php

namespace Limelight\Tests\Classes;

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
     * It converts a simple word to romanji.
     *
     * @test
     */
    public function it_converts_simple_word_to_romanji()
    {
        $results = self::$limelight->parse('行きます');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('ikimasu', $conversion);
    }

    /**
     * It converts a nn word to romanji.
     *
     * @test
     */
    public function it_converts_nn_word_to_romanji()
    {
        $results = self::$limelight->parse('参加');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('sanka', $conversion);
    }

    /**
     * It converts a simple double vowel word to romanji.
     *
     * @test
     */
    public function it_converts_simple_double_vowel_word_to_romanji()
    {
        $results = self::$limelight->parse('お兄さん');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('oniisan', $conversion);
    }

    /**
     * It converts sho.
     *
     * @test
     */
    public function it_converts_sho_to_romanji()
    {
        $results = self::$limelight->parse('初夏');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('syoka', $conversion);
    }

    /**
     * It converts long 0.
     *
     * @test
     */
    public function it_converts_long_o_to_romanji()
    {
        $results = self::$limelight->parse('証券');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('syôken', $conversion);
    }

    /**
     * It converts kyou.
     *
     * @test
     */
    public function it_converts_kyou_to_romanji()
    {
        $results = self::$limelight->parse('今日');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('kyô', $conversion);
    }

    /**
     * It converts multiple words to romanji.
     *
     * @test
     */
    public function it_converts_multiple_words_to_romanji()
    {
        $results = self::$limelight->parse('福岡に住んでいます。');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('Hukuokanisundeimasu.', $conversion);
    }

    /**
     * It converts double k to romanji.
     *
     * @test
     */
    public function it_converts_double_k_to_romanji()
    {
        $results = self::$limelight->parse('結果');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('kekka', $conversion);
    }

    /**
     * It converts double c to romanji.
     *
     * @test
     */
    public function it_converts_double_c_to_romanji()
    {
        $results = self::$limelight->parse('抹茶');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('mattya', $conversion);
    }

    /**
     * It converts nm to mm.
     *
     * @test
     */
    public function it_converts_nm_to_mm()
    {
        $results = self::$limelight->parse('群馬');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('Gunma', $conversion);
    }

    /**
     * It converts n+vowel to n-.
     *
     * @test
     */
    public function it_converts_n_vowel_to_n_dash()
    {
        $results = self::$limelight->parse('簡易');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('kan\'i', $conversion);
    }

    /**
     * It doesnt convert long vowels its not supposed to.
     *
     * @test
     */
    public function it_doesnt_convert_long_vowels_not_listed()
    {
        $results = self::$limelight->parse('お婆さん');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('obâsan', $conversion);
    }

    /**
     * It converts ha to wa.
     *
     * @test
     */
    public function it_converts_ha_to_wa()
    {
        $results = self::$limelight->parse('は');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('ha', $conversion);
    }

    /**
     * It converts he to we.
     *
     * @test
     */
    public function it_converts_he_to_e()
    {
        $results = self::$limelight->parse('へ');

        $conversion = $this->getRomanjiConversion(self::$nihon, $results);

        $this->assertEquals('he', $conversion);
    }
}
