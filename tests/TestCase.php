<?php

namespace Limelight\Tests;

use Limelight\Limelight;
use PHPUnit\Framework\TestCase as PhpunitTestCase;

class TestCase extends PhpunitTestCase
{
    /**
     * Path to test logs.
     *
     * @var string
     */
    protected $logPath = __DIR__.'/Stubs/test.log';

    /**
     * @var Limelight\Limelight
     */
    protected static $limelight;

    /**
     * Set Limelight on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();
    }

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

        foreach ($results as $word) {
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

    /**
     * Parse test phrase and return LimelightResults.
     *
     * @return LimelightResults
     */
    protected function getResults()
    {
        return self::$limelight->parse('東京に行って、パスタを食べてしまった。おいしかったです！');
    }
}
