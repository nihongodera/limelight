<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class FiraaTest extends TestCase
{
    public function testItSetsPartOfSpeechToInterjection(): void
    {
        $results = self::$limelight->parse('えーと');

        $this->assertEquals('interjection', $results->pull(0)->partOfSpeech());
    }
}
