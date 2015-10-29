<?php

namespace Limelight\Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Assert MecabNode object equals expected array.
     *
     * @param MeCab_Node $nodes
     * @param array      $expected
     */
    protected function assertNodeResult($nodes, $expected)
    {
        $count = 0;

        foreach ($nodes as $node) {
            $expectedLine = $expected[$count];

            $this->assertEquals($expectedLine, $node->feature);

            $count += 1;
        }
    }
}
