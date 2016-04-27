<?php

namespace Limelight\tests\Unit;

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
        parent::setUpBeforeClass();
        
        $config = Config::getInstance();

        self::$mecab = $config->make('Limelight\Mecab\Mecab');
    }

    /**
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
     * @test
     */
    public function it_has_parseToString_method()
    {
        $this->assertTrue(
            method_exists(self::$mecab, 'parseToString'),
            'Mecab does not have method parseToString.'
        );
    }
}
