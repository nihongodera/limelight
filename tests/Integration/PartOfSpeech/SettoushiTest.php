<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class SettoushiTest extends TestCase
{
    public function testItChangesPartOfSpeechToPrefix(): void
    {
        $results = self::$limelight->parse('超音速');

        $this->assertEquals('prefix', $results->pull(0)->partOfSpeech());
    }
}
