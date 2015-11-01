<?php

namespace Limelight\Plugins\Library\Romanji;

abstract class StyleDecorator implements RomanjiConverterInterface
{
    /**
     * @var Limelight\Plugins\Library\Romanji\RomanjiConverterInterface
     */
    protected $converter;

    /**
     * Construct.
     *
     * @param RomanjiConverterInterface $converter
     */
    public function __construct(RomanjiConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Convert string to romanji.
     *
     * @param string        $string
     * @param LimelightWord $word
     *
     * @return string
     */
    abstract public function convert($string, $word);
}
