<?php

namespace Limelight\Plugins\Library\Romaji\Styles;

use Limelight\Plugins\Library\Romaji\RomajiConverter;

class KunreiShiki extends RomajiConverter
{
    /**
     * Romaji library.
     *
     * @var array
     */
    protected $conversions;

    /**
     * Conversions for 'n'.
     *
     * @var array
     */
    protected $nConversions = [
        'a' => 'n\'',
        'i' => 'n\'',
        'u' => 'n\'',
        'e' => 'n\'',
        'o' => 'n\'',
        'y' => 'n\'',
    ];

    /**
     * Conversions for particles.
     *
     * @var array
     */
    protected $particleConversions = [
        'ha' => 'wa',
        'he' => 'e',
        'wo' => 'o',
    ];

    /**
     * Conversions for small tsu.
     *
     * @var array
     */
    protected $tsuConversions = [
        //
    ];

    /**
     * Acceptable verb combinations.
     *
     * @var array
     */
    protected $verbCombos = [
        'a' => 'â',
        'u' => 'û',
        'e' => 'ê',
        'o' => 'ô',
    ];

    /**
     * Construct.
     */
    public function __construct()
    {
        $this->conversions = include dirname(__DIR__).'/Lib/KunreiShiki.php';
    }

    /**
     * handle conversion request.
     *
     * @param string        $string
     * @param LimelightWord $word
     *
     * @return string
     */
    public function handle($string, $word)
    {
        return $this->convert($string, $word);
    }
}
