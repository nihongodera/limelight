<?php

declare(strict_types=1);

namespace Limelight\Exceptions;

use Limelight\Config\Config;

class LimelightException extends \Exception
{
    protected bool $debug;

    /**
     * Console output colors.
     */
    protected array $colors = [
        'red'   => "\033[0;31m ",
        'blue'  => "\033[0;36m ",
        'green' => "\033[0;32m ",
        'none'  => "\033[0m",
    ];

    public function __construct(string $message = 'Requested plugin not found.')
    {
        $config = Config::getInstance();

        $this->debug = $config->get('debug');

        parent::__construct($message);
    }

    /**
     * Handle exception.
     */
    public function handle(): string
    {
        if ($this->debug) {
            echo __CLASS__.': '.$this->red("{$this->message}\n");

            $this->printTrace();

            return '';
        }

        return __CLASS__.': '.$this->red("{$this->message}\n");
    }

    /**
     * Print stack trace for exception.
     */
    protected function printTrace(): void
    {
        $trace = $this->getTrace();

        echo "\n";

        echo $this->blue('1. ').'In '.$this->getFile().$this->green(' line '.$this->getLine())."\n";

        $count = 2;

        foreach ($trace as $layer) {
            $number = $this->blue($count.'. ');

            $file = $layer['file'] ?? null;

            $line = isset($layer['line']) ? $this->green(' line '.$layer['line']) : null;

            $class = $layer['class'];

            $function = $this->green($layer['function']);

            $type = ' '.$layer['type'];

            $output = $number.$class.$type.$function.' in '.$file.$line;

            echo $output."\n";

            $count++;
        }
    }

    /**
     * Color text red.
     */
    protected function red(string $text): string
    {
        return $this->colorText($text, 'red');
    }

    /**
     * Color text blue.
     */
    protected function blue(string $text): string
    {
        return $this->colorText($text, 'blue');
    }

    /**
     * Color text green.
     */
    protected function green(string $text): string
    {
        return $this->colorText($text, 'green');
    }

    /**
     * Color text given color.
     */
    private function colorText(string $text, string $color): string
    {
        return $this->colors[$color].$text.$this->colors['none'];
    }
}
