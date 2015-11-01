<?php

namespace Limelight\Tests\Classes;

use Limelight\Limelight;
use Limelight\Tests\TestCase;
use Limelight\Classes\LimelightWord;

class LimelightWordTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    protected static $limelight;

    /**
     * @var Limelight\Classes\LimelightResults
     */
    protected static $results;

    /**
     * Set static limelight on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();

        self::$results = self::$limelight->parse('東京に行って、パスタを食べてしまった。');
    }

    /**
     * It can get raw mecab data off object.
     * 
     * @test
     */
    public function it_can_get_raw_mecab_data()
    {
        $rawMecab = self::$results->getByIndex(0)->rawMecab()->get();

        $this->assertEquals('東京', $rawMecab[0]['literal']);
    }

    /**
     * It can get word off object.
     * 
     * @test
     */
    public function it_can_get_word()
    {
        $word = self::$results->getByIndex(0)->word()->get();

        $this->assertEquals('東京', $word);
    }

    /**
     * It can get lemma off object.
     * 
     * @test
     */
    public function it_can_get_lemma()
    {
        $lemma = self::$results->getByIndex(0)->lemma()->get();

        $this->assertEquals('東京', $lemma);
    }

    /**
     * It can get reading off object.
     * 
     * @test
     */
    public function it_can_get_reading()
    {
        $reading = self::$results->getByIndex(0)->reading()->get();

        $this->assertEquals('トウキョウ', $reading);
    }

    /**
     * It can get pronunciation off object.
     * 
     * @test
     */
    public function it_can_get_pronunciation()
    {
        $pronunciation = self::$results->getByIndex(0)->pronunciation()->get();

        $this->assertEquals('トーキョー', $pronunciation);
    }

    /**
     * It can get partOfSpeech off object.
     * 
     * @test
     */
    public function it_can_get_partOfSpeech()
    {
        $partOfSpeech = self::$results->getByIndex(0)->partOfSpeech()->get();

        $this->assertEquals('proper noun', $partOfSpeech);
    }

    /**
     * It can get grammar off object.
     * 
     * @test
     */
    public function it_can_get_grammar()
    {
        $grammar = self::$results->getByIndex(0)->grammar()->get();

        $this->assertEquals(null, $grammar);
    }

    /**
     * It can get convert to hiragana.
     * 
     * @test
     */
    public function it_can_convert_to_hiragana()
    {
        $reading = self::$results->getByIndex(0)->reading()->toHiragana()->get();

        $this->assertEquals('とうきょう', $reading);
    }

    /**
     * It can get convert to katakana.
     * 
     * @test
     */
    public function it_can_convert_to_katakana()
    {
        $pronunciation = self::$results->getByIndex(0)->pronunciation()->toHiragana()->get();

        $this->assertEquals('とーきょー', $pronunciation);

        $pronunciation = self::$results->getByIndex(0)->pronunciation()->toHiragana()->toKatakana()->get();

        $this->assertEquals('トーキョー', $pronunciation);
    }

    /**
     * Plugin data can be called by property name.
     * 
     * @test
     */
    public function it_can_get_plugin_data_by_property_call()
    {
        $romanji = self::$results->getByIndex(0)->romanji;

        $this->assertEquals('Tōkyō', $romanji);
    }

    /**
     * Plugin data can be called by method.
     * 
     * @test
     */
    public function it_can_get_plugin_data_by_method_call()
    {
        $romanji = self::$results->getByIndex(0)->romanji()->get();

        $this->assertEquals('Tōkyō', $romanji);
    }
}