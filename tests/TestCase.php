<?php

namespace Limelight\Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $logPath = __DIR__.'/Stubs/test.log';

    /**
     * Assert MecabNode object equals expected array.
     *
     * @param Node $nodes
     * @param array      $expected
     */
    protected function assertNodeResult($nodes, $expected)
    {
        $count = 0;

        foreach ($nodes->getNode() as $node) {
            $expectedLine = $expected[$count];

            $this->assertEquals($expectedLine, $node->feature);

            $count += 1;
        }
    }

    /**
     * Get romaji string for results.
     *
     * @param RomajiConverter $converter
     * @param LimelightResults $results
     *
     * @return string
     */
    protected function getRomajiConversion($converter, $results)
    {
        $conversion = '';

        foreach ($results->next() as $word) {
            $reading = mb_convert_kana($word->reading, 'c');

            $conversion .= $converter->handle($reading, $word);
        }

        return $conversion;
    }

    /**
     * Clear the test.log file.
     */
    protected function clearLog()
    {
        file_put_contents($this->logPath, '');
    }

    /**
     * Read test.log file.
     *
     * @return string
     */
    protected function readLog()
    {
        return file_get_contents($this->logPath);
    }
}
