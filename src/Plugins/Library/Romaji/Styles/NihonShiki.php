<?php

declare(strict_types=1);

namespace Limelight\Plugins\Library\Romaji\Styles;

use Limelight\Classes\LimelightWord;
use Limelight\Plugins\Library\Romaji\RomajiConverter;

class NihonShiki extends RomajiConverter
{
    protected array $conversions;

    /**
     * Conversions for 'n'.
     */
    protected array $nConversions = [
        'a' => 'n\'',
        'i' => 'n\'',
        'u' => 'n\'',
        'e' => 'n\'',
        'o' => 'n\'',
        'y' => 'n\'',
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
    protected array $verbCombos = [
        'a' => 'â',
        'u' => 'û',
        'e' => 'ê',
        'o' => 'ô',
    ];

    public function __construct()
    {
        $this->conversions = include dirname(__DIR__).'/Lib/NihonShiki.php';
    }

    /**
     * Handle conversion request.
     */
    public function handle(string $string, LimelightWord $word): string
    {
        return $this->convert($string, $word);
    }
}
