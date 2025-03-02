<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class KandoushiTest extends TestCase
{
    /**
     * @test
     */
    public function it_changes_partOfSpeech_to_interjection(): void
    {
        $results = self::$limelight->parse('あれ');

        $this->assertEquals('interjection', $results->pull(0)->partOfSpeech());
    }
}
