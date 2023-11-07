<?php

declare(strict_types=1);

namespace Limelight\Tests;

use Limelight\Limelight;
use Limelight\Mecab\Node;
use Limelight\Classes\LimelightResults;
use PHPUnit\Framework\TestCase as PhpunitTestCase;
use Limelight\Plugins\Library\Romaji\RomajiConverter;

class TestCase extends PhpunitTestCase
{
    /**
     * Path to test logs.
     */
    protected string $logPath = __DIR__.'/Stubs/test.log';

    protected static Limelight $limelight;

    public static function setUpBeforeClass(): void
    {
        self::$limelight = new Limelight();
    }

    /**
     * Assert MecabNode object equals expected array.
     */
    protected function assertNodeResult(Node $nodes, array $expected): void
    {
        $count = 0;

        foreach ($nodes->getNode() as $node) {
            $expectedLine = $expected[$count];

            $this->assertEquals($expectedLine, $node->feature);

            $count++;
        }
    }

    /**
     * Get romaji string for results.
     */
    protected function getRomajiConversion(RomajiConverter $converter, LimelightResults $results): string
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
    protected function clearLog(): void
    {
        file_put_contents($this->logPath, '');
    }

    /**
     * Read test.log file.
     */
    protected function readLog(): string
    {
        return file_get_contents($this->logPath);
    }

    /**
     * Parse test phrase and return LimelightResults.
     */
    protected function getResults(): LimelightResults
    {
        return self::$limelight->parse('東京に行って、パスタを食べてしまった。おいしかったです！');
    }
}
