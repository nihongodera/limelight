<?php

namespace Limelight\Plugins\Library\Romanji\Styles;

use Limelight\Plugins\Library\Romanji\StyleDecorator;
use Limelight\Plugins\Library\Romanji\RomanjiConverterInterface;

class HepburnModified extends StyleDecorator
{
    /**
     * Romanji library.
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
        'c' => 't',
    ];

    /**
     * Acceptable verb combinations.
     *
     * @var array
     */
    protected $verbCombos = [
        'a' => 'ā',
        'u' => 'ū',
        'e' => 'ē',
        'o' => 'ō',
    ];

    /**
     * Construct.
     */
    public function __construct(RomanjiConverterInterface $converter)
    {
        $this->conversions = include dirname(__DIR__).'/Lib/Hepburn.php';

        parent::__construct($converter);
    }

    /**
     * Convert string to romanji.
     *
     * @param string        $string
     * @param LimelightWord $word
     *
     * @return string
     */
    public function convert($string, $word)
    {
        $this->converter->setVariables(
            $this->conversions,
            $this->verbCombos,
            $this->nConversions,
            $this->particleConversions,
            $this->tsuConversions
        );

        return $this->converter->convert($string, $word);
    }
}
