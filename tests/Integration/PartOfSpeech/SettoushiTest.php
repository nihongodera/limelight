<?php

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class SettoushiTest extends TestCase
{
    /**
     * @test
     */
    public function it_changes_partOfSpeech_to_prefix()
    {
        $results = self::$limelight->parse('超音速');

        $this->assertEquals('prefix', $results->pull(0)->partOfSpeech());
    }
}