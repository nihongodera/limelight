<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class FukushiTest extends TestCase
{
    public function testItChangesPartOfSpeechToAdverb(): void
    {
        $results = self::$limelight->parse('ときどき');

        $this->assertEquals('adverb', $results->pull(0)->partOfSpeech());
    }
}
