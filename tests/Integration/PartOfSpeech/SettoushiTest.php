<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class SettoushiTest extends TestCase
{
    /**
     * @test
     */
    public function it_changes_partOfSpeech_to_prefix(): void
    {
        $results = self::$limelight->parse('超音速');

        $this->assertEquals('prefix', $results->pull(0)->partOfSpeech());
    }
}
