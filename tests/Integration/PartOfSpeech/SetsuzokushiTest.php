<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class SetsuzokushiTest extends TestCase
{
    public function testItChangesPartOfSpeechToConjunction(): void
    {
        $results = self::$limelight->parse('けれども');

        $this->assertEquals('conjunction', $results->pull(0)->partOfSpeech());
    }
}
