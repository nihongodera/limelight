<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class KeiyoushiTest extends TestCase
{
    /**
     * @test
     */
    public function it_changes_partOfSpeech_to_adjective(): void
    {
        $results = self::$limelight->parse('熱い');

        $this->assertEquals('adjective', $results->pull(0)->partOfSpeech());
    }
}
