<?php

declare(strict_types=1);

namespace Limelight\tests\Unit;

use Limelight\Mecab\Mecab;
use Limelight\Config\Config;
use Limelight\Tests\TestCase;

class MecabTest extends TestCase
{
    protected static Mecab $mecab;

    /**
     * Set static mecab on object.
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $config = Config::getInstance();

        self::$mecab = $config->make(Mecab::class);
    }

    /**
     * @test
     */
    public function it_can_be_instantiated(): void
    {
        $config = Config::getInstance();

        $mecab = $config->make(Mecab::class);

        $this->assertIsObject(
            $mecab,
            'Mecab could not be instantiated.'
        );
    }

    /**
     * @test
     */
    public function it_has_parseToNode_method(): void
    {
        $this->assertTrue(
            method_exists(self::$mecab, 'parseToNode'),
            'Mecab does not have method parseToNode.'
        );
    }

    /**
     * @test
     */
    public function it_has_parseToString_method(): void
    {
        $this->assertTrue(
            method_exists(self::$mecab, 'parseToString'),
            'Mecab does not have method parseToString.'
        );
    }
}
