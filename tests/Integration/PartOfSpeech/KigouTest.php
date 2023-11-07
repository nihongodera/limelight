<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class KigouTest extends TestCase
{
    /**
     * @test
     */
    public function it_changes_partOfSpeech_to_symbol(): void
    {
        $results = self::$limelight->parse('。');

        $this->assertEquals('symbol', $results->pull(0)->partOfSpeech());
    }
}
