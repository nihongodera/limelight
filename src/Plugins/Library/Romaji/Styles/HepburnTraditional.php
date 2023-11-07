<?php

declare(strict_types=1);

namespace Limelight\Plugins\Library\Romaji\Styles;

use Limelight\Classes\LimelightWord;
use Limelight\Plugins\Library\Romaji\RomajiConverter;

class HepburnTraditional extends RomajiConverter
{
    protected array $conversions;

    /**
     * Conversions for 'n'.
     */
    protected array $nConversions = [
        'b' => 'm',
        'm' => 'm',
        'p' => 'm',
        'a' => 'n-',
        'i' => 'n-',
        'u' => 'n-',
        'e' => 'n-',
        'o' => 'n-',
        'y' => 'n-',
    ];

    /**
     * Conversions for particles.
     */
    protected array $particleConversions = [
        'ha' => 'wa',
        'he' => 'e',
    ];

    /**
     * Conversions for small tsu.
     */
    protected array $tsuConversions = [
        'c' => 't',
    ];

    /**
     * Acceptable verb combinations.
     */
    protected array $verbCombos = [
        'u' => 'ū',
        'o' => 'ō',
    ];

    public function __construct()
    {
        $this->conversions = include dirname(__DIR__).'/Lib/Hepburn.php';
    }

    /**
     * Handle conversion request.
     */
    public function handle(string $string, LimelightWord $word): string
    {
        return $this->convert($string, $word);
    }
}
