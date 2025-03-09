<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class RentaishiTest extends TestCase
{
    public function testItChangesPartOfSpeechToDeterminer(): void
    {
        $results = self::$limelight->parse('いわゆる');

        $this->assertEquals('determiner', $results->pull(0)->partOfSpeech());
    }
}
