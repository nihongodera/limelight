<?php

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class FukushiTest extends TestCase
{
    /**
     * @test
     */
    public function it_changes_partOfSpeech_to_adverb()
    {
        $results = self::$limelight->parse('ときどき');

        $this->assertEquals('adverb', $results->pull(0)->partOfSpeech());
    }
}
