<?php

namespace Limelight\tests\Unit;

use Limelight\Tests\TestCase;
use Limelight\Helpers\JapaneseHelpers;

class JapaneseHelpersTest extends TestCase
{
    use JapaneseHelpers;

    /**
     * @test
     */
    public function hasKanji_finds_kanji()
    {
        $bool = $this->hasKanji('行きます');

        $this->assertTrue($bool);
    }

    /**
     * @test
     */
    public function hasKanji_returns_false_for_no_kanji()
    {
        $bool = $this->hasKanji('おいしい！');

        $this->assertFalse($bool);
    }

    /**
     * @test
     */
    public function hasKanji_returns_false_for_english()
    {
        $bool = $this->hasKanji('Hello');

        $this->assertFalse($bool);
    }

    /**
     * @test
     */
    public function isKatakana_finds_katakana()
    {
        $bool = $this->isKatakana('ア');

        $this->assertEquals(1, $bool);
    }

    /**
     * @test
     */
    public function isKatakana_returns_false_for_hiragana()
    {
        $bool = $this->isKatakana('は');

        $this->assertFalse($bool);
    }

    /**
     * @test
     */
    public function isKatakana_returns_false_for_kanji()
    {
        $bool = $this->isKatakana('例');

        $this->assertFalse($bool);
    }

    /**
     * @test
     */
    public function isKatakana_returns_false_for_english()
    {
        $bool = $this->isKatakana('p');

        $this->assertFalse($bool);
    }

    /**
     * @test
     */
    public function getChars_splits_english_string()
    {
        $string = 'hello';

        $count = strlen($string);

        $array = $this->getChars($string);

        for ($i = 0; $i < $count; $i++) {
            $this->assertEquals(substr($string, $i, 1), $array[$i]);
        }
    }

    /**
     * @test
     */
    public function getChars_splits_japanese_string()
    {
        $string = 'パスタが食べたいです！';

        $count = mb_strlen($string);

        $array = $this->getChars($string);

        for ($i = 0; $i < $count; $i++) {
            $this->assertEquals(mb_substr($string, $i, 1), $array[$i]);
        }
    }
}
