<?php

namespace Limelight\tests\Integration;

use Limelight\Limelight;
use Limelight\Config\Config;
use Limelight\Tests\TestCase;
use Limelight\Classes\LimelightResults;

class CollectionMethodsTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    protected static $limelight;

    /**
     * @var Limelight\Classes\LimelightResults
     */
    protected static $results;

    /**
     * Set static limelight on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();

        self::$results = self::$limelight->parse('音楽を聴きます。');
    }

    /**
     * @test
     */
    public function map_returns_array()
    {
        $answer = static::$results->map(function ($item, $key) {
            return $item->get();
        });

        $this->assertEquals(['音楽', 'を', '聴きます', '。'], $answer);
    }

    /**
     * @test
     */
    public function filter_returns_filtered_array()
    {
        $answer = static::$results->filter(function ($item, $key) {
            return $item->get() !== '音楽';
        });

        $this->assertCount(3, $answer);
    }
}
