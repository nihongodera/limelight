<?php

declare(strict_types=1);

namespace Limelight\tests\Unit;

use Limelight\Helpers\JapaneseHelpers;
use Limelight\Tests\TestCase;

class JapaneseHelpersTest extends TestCase
{
    use JapaneseHelpers;

    public function testHasKanjiFindsKanji(): void
    {
        $bool = $this->hasKanji('行きます');

        $this->assertTrue($bool);
    }

    public function testHasKanjiReturnsFalseForNoKanji(): void
    {
        $bool = $this->hasKanji('おいしい！');

        $this->assertFalse($bool);
    }

    public function testHasKanjiReturnsFalseForEnglish(): void
    {
        $bool = $this->hasKanji('Hello');

        $this->assertFalse($bool);
    }

    public function testIsKatakanaFindsKatakana(): void
    {
        $bool = $this->isKatakana('ア');

        $this->assertEquals(1, $bool);
    }

    public function testIsKatakanaReturnsFalseForHiragana(): void
    {
        $bool = $this->isKatakana('は');

        $this->assertFalse($bool);
    }

    public function testIsKatakanaReturnsFalseForKanji(): void
    {
        $bool = $this->isKatakana('例');

        $this->assertFalse($bool);
    }

    public function testIsKatakanaReturnsFalseForEnglish(): void
    {
        $bool = $this->isKatakana('p');

        $this->assertFalse($bool);
    }

    public function testGetCharsSplitsEnglishString(): void
    {
        $string = 'hello';

        $count = strlen($string);

        $array = $this->getChars($string);

        for ($i = 0; $i < $count; $i++) {
            $this->assertEquals($string[$i], $array[$i]);
        }
    }

    public function testGetCharsSplitsJapaneseString(): void
    {
        $string = 'パスタが食べたいです！';

        $count = mb_strlen($string);

        $array = $this->getChars($string);

        for ($i = 0; $i < $count; $i++) {
            $this->assertEquals(mb_substr($string, $i, 1), $array[$i]);
        }
    }
}
