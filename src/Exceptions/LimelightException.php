<?php

namespace Limelight\Exceptions;

class LimelightException extends \Exception
{
    /**
     * Console output colors.
     *
     * @var array
     */
    protected $colors = [
        'red' => "\033[0;31m ",
        'blue' => "\033[0;36m ",
        'green' => "\033[0;32m ",
        'none' => "\033[0m",
    ];

    /**
     * Handle exception.
     *
     * @return exit
     */
    public function handle()
    {
        echo  __CLASS__.': '. $this->red("{$this->message}\n");

        $this->printTrace();
    }

    /**
     * Print stack trace for exception.
     */
    protected function printTrace()
    {
        $trace = $this->getTrace();

        echo "\n";

        echo $this->blue('1. ').'In '.$this->getFile(). $this->green(' line '.$this->getLine())."\n";

        $count = 2;

        foreach ($trace as $layer) {
            $number = $this->blue($count . '. ');

            $file = (isset($layer['file']) ?: null);

            $line = (isset($layer['line']) ? $this->green(' line '.$layer['line']) : null);

            $class = $layer['class'];

            $function = $this->green($layer['function']);

            $type = $layer['type'];

            $output = $number.$class.$type.$function.' in '.$file.$line;

            echo $output."\n";

            $count += 1;
        }
    }

    /**
     * Color text red.
     *
     * @param string $text
     *
     * @return string
     */
    protected function red($text)
    {
        return $this->colorText($text, 'red');
    }

    /**
     * Color text blue.
     *
     * @param string $text
     *
     * @return string
     */
    protected function blue($text)
    {
        return $this->colorText($text, 'blue');
    }

    /**
     * Color text green.
     *
     * @param string $text
     *
     * @return string
     */
    protected function green($text)
    {
        return $this->colorText($text, 'green');
    }

    /**
     * Color text given color.
     *
     * @param string $text
     * @param string $color
     *
     * @return string
     */
    private function colorText($text, $color)
    {
        return $this->colors[$color].$text.$this->colors['none'];
    }
}
