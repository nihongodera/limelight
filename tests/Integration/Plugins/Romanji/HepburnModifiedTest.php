<?php

namespace Limelight\tests\Integration\Plugins\Romanji;

use Limelight\Limelight;
use Limelight\Tests\TestCase;
use Limelight\Plugins\Library\Romanji\Styles\HepburnModified;

class HepburnModifiedTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    private static $limelight;

    /**
     * @var Limelight\Plugins\Library\Romanji\Styles\HepburnModified
     */
    protected static $hepburn;

    /**
     * Set static Limelight and Hepburn on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();

        self::$hepburn = new HepburnModified();
    }

    /**
     * @test
     */
    public function it_converts_simple_word_to_romanji()
    {
        $results = self::$limelight->parse('行きます');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('ikimasu', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_nn_word_to_romanji()
    {
        $results = self::$limelight->parse('参加');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('sanka', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_simple_double_vowel_word_to_romanji()
    {
        $results = self::$limelight->parse('お兄さん');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('oniisan', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_sho_to_romanji()
    {
        $results = self::$limelight->parse('初夏');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('shoka', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_shou_to_romanji()
    {
        $results = self::$limelight->parse('証券');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('shōken', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_kyou_to_romanji()
    {
        $results = self::$limelight->parse('今日');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('kyō', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_multiple_words_to_romanji()
    {
        $results = self::$limelight->parse('福岡に住んでいます。');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('Fukuokanisundeimasu.', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_double_k_to_romanji()
    {
        $results = self::$limelight->parse('結果');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('kekka', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_double_c_to_romanji()
    {
        $results = self::$limelight->parse('抹茶');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('matcha', $conversion);
    }

    /**
     * @test
     */
    public function it_doesnt_convert_double_a_when_seperate_words()
    {
        $results = self::$limelight->parse('邪悪');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('jaaku', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_double_a_when_not_seperate_words()
    {
        $results = self::$limelight->parse('お婆さん');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('obāsan', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_double_u_when_not_seperate_words()
    {
        $results = self::$limelight->parse('数学');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('sūgaku', $conversion);
    }

    /**
     * @test
     */
    public function it_doesnt_convert_double_u_when_seperate_words()
    {
        $results = self::$limelight->parse('湖');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('mizuumi', $conversion);
    }

    /**
     * @test
     */
    public function it_doesnt_convert_double_u_on_vowels()
    {
        $results = self::$limelight->parse('食う');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('kuu', $conversion);
    }

    /**
     * @test
     */
    public function it_doesnt_convert_double_i()
    {
        $results = self::$limelight->parse('お兄さん');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('oniisan', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_double_e_when_not_seperate_words()
    {
        $results = self::$limelight->parse('お姉さん');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('onēsan', $conversion);
    }

    /**
     * @test
     */
    public function it_doesnt_convert_double_e_when_seperate_words()
    {
        $results = self::$limelight->parse('濡れ縁');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('nureen', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_double_o_when_not_seperate_words()
    {
        $results = self::$limelight->parse('小躍り');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('koodori', $conversion);
    }

    /**
     * @test
     */
    public function it_doesnt_convert_double_o_when_seperate_words()
    {
        $results = self::$limelight->parse('氷');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('kōri', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_ou_when_not_seperate_words()
    {
        $results = self::$limelight->parse('迷う');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('mayou', $conversion);
    }

    /**
     * @test
     */
    public function it_doesnt_convert_ou_when_seperate_words()
    {
        $results = self::$limelight->parse('学校');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('gakkō', $conversion);
    }

    /**
     * @test
     */
    public function it_doesnt_convert_nm()
    {
        $results = self::$limelight->parse('群馬');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('Gunma', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_n_vowel_to_n_apostrophe()
    {
        $results = self::$limelight->parse('簡易');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('kan\'i', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_ha_to_wa()
    {
        $results = self::$limelight->parse('は');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('wa', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_he_to_e()
    {
        $results = self::$limelight->parse('へ');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('e', $conversion);
    }

    /**
     * @test
     */
    public function it_converts_wo_to_o()
    {
        $results = self::$limelight->parse('を');

        $conversion = $this->getRomanjiConversion(self::$hepburn, $results);

        $this->assertEquals('o', $conversion);
    }
}
