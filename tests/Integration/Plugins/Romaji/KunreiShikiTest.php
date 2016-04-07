<?php

namespace Limelight\tests\Integration\Plugins\Romaji;

use Limelight\Limelight;
use Limelight\Tests\TestCase;
use Limelight\Plugins\Library\Romaji\Styles\KunreiShiki;

class KunreiShikiTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    private static $limelight;

    /**
     * @var Limelight\Plugins\Library\Romaji\Styles\KunreiShiki
     */
    protected static $kunrei;

    /**
     * Set static Limelight and Kunrei on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();

        self::$kunrei = new KunreiShiki();
    }

    /**
     * @test
     */
    public function it_converts_simple_word_to_romaji()
    {
        $results = self::$limelight->parse('行きます');

        $conversion = $this->getRomajiConversion(self::$kunrei, $results);

        $this->assertEquals('ikimasu', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_nn_word_to_romaji()
    {
        $results = self::$limelight->parse('参加');

        $conversion = $this->getRomajiConversion(self::$kunrei, $results);

        $this->assertEquals('sanka', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_simple_double_vowel_word_to_romaji()
    {
        $results = self::$limelight->parse('お兄さん');

        $conversion = $this->getRomajiConversion(self::$kunrei, $results);

        $this->assertEquals('oniisan', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_sho_to_romaji()
    {
        $results = self::$limelight->parse('初夏');

        $conversion = $this->getRomajiConversion(self::$kunrei, $results);

        $this->assertEquals('syoka', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_long_o_to_romaji()
    {
        $results = self::$limelight->parse('証券');

        $conversion = $this->getRomajiConversion(self::$kunrei, $results);

        $this->assertEquals('syôken', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_kyou_to_romaji()
    {
        $results = self::$limelight->parse('今日');

        $conversion = $this->getRomajiConversion(self::$kunrei, $results);

        $this->assertEquals('kyô', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_multiple_words_to_romaji()
    {
        $results = self::$limelight->parse('福岡に住んでいます。');

        $conversion = $this->getRomajiConversion(self::$kunrei, $results);

        $this->assertEquals('Hukuokanisundeimasu.', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_double_k_to_romaji()
    {
        $results = self::$limelight->parse('結果');

        $conversion = $this->getRomajiConversion(self::$kunrei, $results);

        $this->assertEquals('kekka', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_double_c_to_romaji()
    {
        $results = self::$limelight->parse('抹茶');

        $conversion = $this->getRomajiConversion(self::$kunrei, $results);

        $this->assertEquals('mattya', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_nm_to_mm()
    {
        $results = self::$limelight->parse('群馬');

        $conversion = $this->getRomajiConversion(self::$kunrei, $results);

        $this->assertEquals('Gunma', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_n_vowel_to_n_dash()
    {
        $results = self::$limelight->parse('簡易');

        $conversion = $this->getRomajiConversion(self::$kunrei, $results);

        $this->assertEquals('kan\'i', $conversion);
    }

    /**
     * @test
     */
    public function it_doesnt_convert_long_vowels_not_listed()
    {
        $results = self::$limelight->parse('お婆さん');

        $conversion = $this->getRomajiConversion(self::$kunrei, $results);

        $this->assertEquals('obâsan', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_ha_to_wa()
    {
        $results = self::$limelight->parse('は');

        $conversion = $this->getRomajiConversion(self::$kunrei, $results);

        $this->assertEquals('wa', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_he_to_e()
    {
        $results = self::$limelight->parse('へ');

        $conversion = $this->getRomajiConversion(self::$kunrei, $results);

        $this->assertEquals('e', $conversion);
    }
}
