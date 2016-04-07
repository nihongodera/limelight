<?php

namespace Limelight\Plugins\Library\Romaji\Styles;

use Limelight\Plugins\Library\Romaji\RomajiConverter;

class HepburnTraditional extends RomajiConverter
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
     *
     * @var array
     */
    protected $particleConversions = [
        'ha' => 'wa',
        'he' => 'e',
    ];

    /**
     * Conversions for small tsu.
     *
     * @var array
     */
    protected $tsuConversions = [
        'c' => 't',
    ];

    /**
     * Acceptable verb combinations.
     *
     * @var array
     */
    protected $verbCombos = [
        'u' => 'ū',
        'o' => 'ō',
    ];

    /**
     * Construct.
     */
    public function __construct()
    {
        $this->conversions = include dirname(__DIR__).'/Lib/Hepburn.php';
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
