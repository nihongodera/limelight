<?php

declare(strict_types=1);

namespace Limelight\tests\Unit;

use Limelight\Tests\TestCase;
use Limelight\Parse\PartOfSpeech\POSRegistry;

class POSRegistryTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_an_instance_of_itself(): void
    {
        $registry = POSRegistry::getInstance();

        $this->assertInstanceOf(POSRegistry::class, $registry);
    }

    /**
     * @test
     */
    public function it_sets_a_class_in_the_registry_and_gets_same(): void
    {
        $registry = POSRegistry::getInstance();

        $class1 = $registry->getClass('Meishi');

        $hash1 = spl_object_hash($class1);

        $class2 = $registry->getClass('Meishi');

        $hash2 = spl_object_hash($class2);

        $this->AssertEquals($hash1, $hash2);
    }
}
