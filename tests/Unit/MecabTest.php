<?php

declare(strict_types=1);

namespace Limelight\tests\Unit;

use Limelight\Config\Config;
use Limelight\Mecab\Mecab;
use Limelight\Tests\TestCase;

class MecabTest extends TestCase
{
    protected static Mecab $mecab;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $config = Config::getInstance();

        self::$mecab = $config->make(Mecab::class);
    }

    public function testItCanBeInstantiated(): void
    {
        $config = Config::getInstance();

        $mecab = $config->make(Mecab::class);

        $this->assertIsObject(
            $mecab,
            'Mecab could not be instantiated.'
        );
    }

    public function testItHasParseToNodeMethod(): void
    {
        $this->assertTrue(
            method_exists(self::$mecab, 'parseToNode'),
            'Mecab does not have method parseToNode.'
        );
    }

    public function testItHasParseToStringMethod(): void
    {
        $this->assertTrue(
            method_exists(self::$mecab, 'parseToString'),
            'Mecab does not have method parseToString.'
        );
    }
}
