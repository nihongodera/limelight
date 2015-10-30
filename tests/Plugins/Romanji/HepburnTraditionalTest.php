<?php

namespace Limelight\Tests\Classes;

use Limelight\Limelight;
use Limelight\Tests\TestCase;
use Limelight\Plugins\Library\Romanji\Styles\HepburnTraditional;

class HepburnTraditionalTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    private static $limelight;

    /**
     * @var hepburn\hepburn
     */
    protected static $hepburn;

    /**
     * Set static hepburn on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();

        self::$hepburn = new HepburnTraditional();
    }

    /**
     * It converts a simple word to romanji.
     * 
     * @test
     */
    public function it_converts_simple_word_to_romanji()
    {
        $results = self::$limelight->parse('行きます');

        $conversion = '';

        foreach ($results->getNext() as $word) {
            $reading = mb_convert_kana($word->reading, 'c');

            $conversion .= self::$hepburn->convert($reading, $word);
        }

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

        $conversion = '';

        foreach ($results->getNext() as $word) {
            $reading = mb_convert_kana($word->reading, 'c');

            $conversion .= self::$hepburn->convert($reading, $word);
        }

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

        $conversion = '';

        foreach ($results->getNext() as $word) {
            $reading = mb_convert_kana($word->reading, 'c');

            $conversion .= self::$hepburn->convert($reading, $word);
        }

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

        $conversion = '';

        foreach ($results->getNext() as $word) {
            $reading = mb_convert_kana($word->reading, 'c');

            $conversion .= self::$hepburn->convert($reading, $word);
        }

        $this->assertEquals('shoka', $conversion);
    }

    /**
     * It converts shou.
     * 
     * @test
     */
    public function it_converts_shou_to_romanji()
    {
        $results = self::$limelight->parse('証券');

        $conversion = '';

        foreach ($results->getNext() as $word) {
            $reading = mb_convert_kana($word->reading, 'c');

            $conversion .= self::$hepburn->convert($reading, $word);
        }

        $this->assertEquals('shouken', $conversion);
    }

    /**
     * It converts kyou.
     * 
     * @test
     */
    public function it_converts_kyou_to_romanji()
    {
        $results = self::$limelight->parse('今日');

        $conversion = '';

        foreach ($results->getNext() as $word) {
            $reading = mb_convert_kana($word->reading, 'c');

            $conversion .= self::$hepburn->convert($reading, $word);
        }

        $this->assertEquals('kyou', $conversion);
    }

    /**
     * It converts multiple words to romanji.
     * 
     * @test
     */
    public function it_converts_multiple_words_to_romanji()
    {
        $results = self::$limelight->parse('福岡に住んでいます。');

        $conversion = '';

        foreach ($results->getNext() as $word) {
            $reading = mb_convert_kana($word->reading, 'c');

            $conversion .= self::$hepburn->convert($reading, $word);
        }

        $this->assertEquals('Fukuokanisundeimasu.', $conversion);
    }

    /**
     * It converts double k to romanji.
     * 
     * @test
     */
    public function it_converts_double_k_to_romanji()
    {
        $results = self::$limelight->parse('結果');

        $conversion = '';

        foreach ($results->getNext() as $word) {
            $reading = mb_convert_kana($word->reading, 'c');

            $conversion .= self::$hepburn->convert($reading, $word);
        }

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

        $conversion = '';

        foreach ($results->getNext() as $word) {
            $reading = mb_convert_kana($word->reading, 'c');

            $conversion .= self::$hepburn->convert($reading, $word);
        }

        $this->assertEquals('matcha', $conversion);
    }
}