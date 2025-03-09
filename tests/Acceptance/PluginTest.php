<?php

declare(strict_types=1);

namespace Limelight\tests\Acceptance;

use Limelight\Tests\TestCase;

class PluginTest extends TestCase
{
    public function testItRunsPluginsByDefault(): void
    {
        $results = self::$limelight->parse('燃える');

        $furigana = '';

        foreach ($results as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>燃</rb><rp>(</rp><rt>も</rt><rp>)</rp></ruby>える', $furigana);
    }

    public function testItTurnsPluginsOffIfFalseIsPassedAsSecondParameter(): void
    {
        $results = self::$limelight->parse('燃える', false);

        $furigana = $results->furigana();

        $this->assertNull($furigana);
    }
}
