<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class RentaishiTest extends TestCase
{
    /**
     * @test
     */
    public function it_changes_partOfSpeech_to_determiner(): void
    {
        $results = self::$limelight->parse('いわゆる');

        $this->assertEquals('determiner', $results->pull(0)->partOfSpeech());
    }
}
