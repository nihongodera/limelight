<?php

namespace Limelight\Tests\Classes;

use Limelight\Config\Config;
use Limelight\Tests\TestCase;

class MecabTest extends TestCase
{
    /**
     * @var implements Limelight\Mecab\Mecab
     */
    protected static $mecab;

    /**
     * Set static mecab on object.
     */
    public static function setUpBeforeClass()
    {
        $config = Config::getInstance();

        self::$mecab = $config->make('Limelight\Mecab\Mecab');
    }

    /**
     * Class bound to Mecab.php can be instantiated.
     *
     * @test
     */
    public function it_can_be_instantiated()
    {
        $config = Config::getInstance();

        $mecab = $config->make('Limelight\Mecab\Mecab');

        $this->assertTrue(
            is_object($mecab),
            'Mecab could not be instantiated.'
        );
    }

    /**
     * Mecab has parseToNode method.
     *
     * @test
     */
    public function it_has_parseToNode_method()
    {
        $this->assertTrue(
            method_exists(self::$mecab, 'parseToNode'),
            'Mecab does not have method parseToNode.'
        );
    }

    /**
     * Mecab has parseToString method.
     *
     * @test
     */
    public function it_has_parseToString_method()
    {
        $this->assertTrue(
            method_exists(self::$mecab, 'parseToString'),
            'Mecab does not have method parseToString.'
        );
    }

    /**
     * Mecab has split method.
     *
     * @test
     */
    public function it_has_split_method()
    {
        $this->assertTrue(
            method_exists(self::$mecab, 'split'),
            'Mecab does not have method split.'
        );
    }
}
