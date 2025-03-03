<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class KigouTest extends TestCase
{
    public function testItChangesPartOfSpeechToSymbol(): void
    {
        $results = self::$limelight->parse('。');

        $this->assertEquals('symbol', $results->pull(0)->partOfSpeech());
    }
}
