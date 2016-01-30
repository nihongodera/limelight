<?php

namespace Limelight\tests\Acceptance;

use Limelight\Limelight;
use Limelight\Tests\TestCase;

class PluginTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    protected static $limelight;

    /**
     * Set static limelight on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();
    }

    /**
     * @test
     */
    public function it_runs_plugins_by_default()
    {
        $results = self::$limelight->parse('燃える');

        $furigana = '';

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('<ruby><rb>燃</rb><rp>(</rp><rt>も</rt><rp>)</rp></ruby>える', $furigana);
    }

    /**
     * @test
     */
    public function it_turns_plugins_off_if_false_is_passed_as_second_parameter()
    {
        $results = self::$limelight->parse('燃える', false);

        $furigana = '';

        foreach ($results->next() as $word) {
            $furigana .= $word->plugin('Furigana');
        }

        $this->assertEquals('', $furigana);
    }
}
