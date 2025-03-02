<?php

declare(strict_types=1);

namespace Limelight\Plugins\Library\Romaji\Styles;

use Limelight\Classes\LimelightWord;
use Limelight\Plugins\Library\Romaji\RomajiConverter;

class Wapuro extends RomajiConverter
{
    protected array $conversions;

    /**
     * Conversions for 'n'.
     */
    protected array $nConversions = [
        'a' => 'nn',
        'i' => 'nn',
        'u' => 'nn',
        'e' => 'nn',
        'o' => 'nn',
        'y' => 'nn',
    ];

    /**
     * Conversions for particles.
     */
    protected array $particleConversions = [];

    /**
     * Conversions for small tsu.
     */
    protected array $tsuConversions = [];

    /**
     * Acceptable verb combinations.
     */
    protected array $verbCombos = [];

    public function __construct()
    {
        $this->conversions = include dirname(__DIR__).'/Lib/Wapuro.php';
    }

    /**
     * Handle conversion request.
     */
    public function handle(string $string, LimelightWord $word): string
    {
        return $this->convert($string, $word);
    }
}
