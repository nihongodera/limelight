<?php

namespace Limelight\Plugins\Library\Romaji\Styles;

use Limelight\Plugins\Library\Romaji\RomajiConverter;

class Wapuro extends RomajiConverter
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
        'a' => 'nn',
        'i' => 'nn',
        'u' => 'nn',
        'e' => 'nn',
        'o' => 'nn',
        'y' => 'nn',
    ];

    /**
     * Conversions for particles.
     *
     * @var array
     */
    protected $particleConversions = [
        //
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
        //
    ];

    /**
     * Construct.
     */
    public function __construct()
    {
        $this->conversions = include dirname(__DIR__) . '/Lib/Wapuro.php';
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
