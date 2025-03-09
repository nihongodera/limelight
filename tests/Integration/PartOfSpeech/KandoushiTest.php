<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class KandoushiTest extends TestCase
{
    public function testItChangesPartOfSpeechToInterjection(): void
    {
        $results = self::$limelight->parse('あれ');

        $this->assertEquals('interjection', $results->pull(0)->partOfSpeech());
    }
}
