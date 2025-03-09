<?php

declare(strict_types=1);

namespace Limelight\tests\Unit;

use Limelight\Parse\PartOfSpeech\POSRegistry;
use Limelight\Tests\TestCase;

class POSRegistryTest extends TestCase
{
    public function testItGetsAnInstanceOfItself(): void
    {
        $registry = POSRegistry::getInstance();

        $this->assertInstanceOf(POSRegistry::class, $registry);
    }

    public function testItSetsAClassInTheRegistryAndGetsSame(): void
    {
        $registry = POSRegistry::getInstance();

        $class1 = $registry->getClass('Meishi');

        $hash1 = spl_object_hash($class1);

        $class2 = $registry->getClass('Meishi');

        $hash2 = spl_object_hash($class2);

        $this->AssertEquals($hash1, $hash2);
    }
}
