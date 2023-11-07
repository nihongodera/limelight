<?php

declare(strict_types=1);

namespace Limelight\tests\Unit;

use Limelight\Tests\TestCase;
use Limelight\Helpers\JapaneseHelpers;

class JapaneseHelpersTest extends TestCase
{
    use JapaneseHelpers;

    /**
     * @test
     */
    public function hasKanji_finds_kanji(): void
    {
        $bool = $this->hasKanji('行きます');

        $this->assertTrue($bool);
    }

    /**
     * @test
     */
    public function hasKanji_returns_false_for_no_kanji(): void
    {
        $bool = $this->hasKanji('おいしい！');

        $this->assertFalse($bool);
    }

    /**
     * @test
     */
    public function hasKanji_returns_false_for_english(): void
    {
        $bool = $this->hasKanji('Hello');

        $this->assertFalse($bool);
    }

    /**
     * @test
     */
    public function isKatakana_finds_katakana(): void
    {
        $bool = $this->isKatakana('ア');

        $this->assertEquals(1, $bool);
    }

    /**
     * @test
     */
    public function isKatakana_returns_false_for_hiragana(): void
    {
        $bool = $this->isKatakana('は');

        $this->assertFalse($bool);
    }

    /**
     * @test
     */
    public function isKatakana_returns_false_for_kanji(): void
    {
        $bool = $this->isKatakana('例');

        $this->assertFalse($bool);
    }

    /**
     * @test
     */
    public function isKatakana_returns_false_for_english(): void
    {
        $bool = $this->isKatakana('p');

        $this->assertFalse($bool);
    }

    /**
     * @test
     */
    public function getChars_splits_english_string(): void
    {
        $string = 'hello';

        $count = strlen($string);

        $array = $this->getChars($string);

        for ($i = 0; $i < $count; $i++) {
            $this->assertEquals($string[$i], $array[$i]);
        }
    }

    /**
     * @test
     */
    public function getChars_splits_japanese_string(): void
    {
        $string = 'パスタが食べたいです！';

        $count = mb_strlen($string);

        $array = $this->getChars($string);

        for ($i = 0; $i < $count; $i++) {
            $this->assertEquals(mb_substr($string, $i, 1), $array[$i]);
        }
    }
}
