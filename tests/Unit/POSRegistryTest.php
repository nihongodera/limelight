<?php

namespace Limelight\tests\Unit;

use Limelight\Limelight;
use Limelight\Tests\TestCase;
use Limelight\Parse\PartOfSpeech\POSRegistry;

class POSRegistryTest extends TestCase
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
    public function it_gets_an_instance_of_itself()
    {
        $registry = POSRegistry::getInstance();

        $this->assertInstanceOf('Limelight\Parse\PartOfSpeech\POSRegistry', $registry);
    }

    /**
     * @test
     */
    public function it_sets_a_class_in_the_registry_and_gets_same()
    {
        $registry = POSRegistry::getInstance();

        $class1 = $registry->getClass('Meishi');

        $hash1 = spl_object_hash($class1);

        $class2 = $registry->getClass('Meishi');

        $hash2 = spl_object_hash($class1);

        $this->AssertEquals($hash1, $hash2);
    }
}