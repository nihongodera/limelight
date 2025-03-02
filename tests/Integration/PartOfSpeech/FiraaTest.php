<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class FiraaTest extends TestCase
{
    /**
     * @test
     */
    public function it_sets_partOfSpeech_to_interjection(): void
    {
        $results = self::$limelight->parse('えーと');

        $this->assertEquals('interjection', $results->pull(0)->partOfSpeech());
    }
}
