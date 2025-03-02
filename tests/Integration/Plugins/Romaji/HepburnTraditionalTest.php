<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\Plugins\Romaji;

use Limelight\Tests\TestCase;
use Limelight\Plugins\Library\Romaji\Styles\HepburnTraditional;

class HepburnTraditionalTest extends TestCase
{
    protected static HepburnTraditional $hepburn;

    /**
     * Set static hepburn on object.
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$hepburn = new HepburnTraditional();
    }

    /**
     * @test
     */
    public function it_converts_simple_word_to_romaji(): void
    {
        $results = self::$limelight->parse('行きます');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('ikimasu', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_nn_word_to_romaji(): void
    {
        $results = self::$limelight->parse('参加');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('sanka', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_simple_double_vowel_word_to_romaji(): void
    {
        $results = self::$limelight->parse('お兄さん');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('oniisan', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_sho_to_romaji(): void
    {
        $results = self::$limelight->parse('初夏');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('shoka', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_long_o_to_romaji(): void
    {
        $results = self::$limelight->parse('証券');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('shōken', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_kyou_to_romaji(): void
    {
        $results = self::$limelight->parse('今日');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('kyō', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_multiple_words_to_romaji(): void
    {
        $results = self::$limelight->parse('福岡に住んでいます。');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('Fukuokanisundeimasu.', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_double_k_to_romaji(): void
    {
        $results = self::$limelight->parse('結果');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('kekka', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_double_c_to_romaji(): void
    {
        $results = self::$limelight->parse('抹茶');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('matcha', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_nm_to_mm(): void
    {
        $results = self::$limelight->parse('群馬');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('Gumma', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_n_vowel_to_n_dash(): void
    {
        $results = self::$limelight->parse('簡易');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('kan-i', $conversion);
    }

    /**
     * @test
     */
    public function it_doesnt_convert_long_vowels_not_listed(): void
    {
        $results = self::$limelight->parse('お婆さん');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('obaasan', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_ha_to_wa(): void
    {
        $results = self::$limelight->parse('は');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('wa', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_he_to_e(): void
    {
        $results = self::$limelight->parse('へ');

        $conversion = $this->getRomajiConversion(self::$hepburn, $results);

        $this->assertEquals('e', $conversion);
    }
}
