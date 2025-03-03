<?php

declare(strict_types=1);

namespace Limelight\tests\Integration\PartOfSpeech;

use Limelight\Tests\TestCase;

class JoshiTest extends TestCase
{
    public function testItChangesPartOfSpeechToPostposition(): void
    {
        $results = self::$limelight->parse('を');

        $this->assertEquals('postposition', $results->pull(0)->partOfSpeech());
    }

    public function testItAttachesSetsuzokujoshiToPreviousWord(): void
    {
        $results = self::$limelight->parse('行けば');

        $this->assertEquals('行けば', $results->string('word'));

        $words = $results->all();

        $this->assertCount(1, $words);
    }
}
