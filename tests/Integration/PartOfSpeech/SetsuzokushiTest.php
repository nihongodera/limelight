<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class SetsuzokushiTest extends TestCase
{
    /**
     * @test
     */
    public function it_changes_partOfSpeech_to_conjunction(): void
    {
        $results = self::$limelight->parse('けれども');

        $this->assertEquals('conjunction', $results->pull(0)->partOfSpeech());
    }
}
