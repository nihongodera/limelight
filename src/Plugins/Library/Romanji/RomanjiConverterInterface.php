<?php

namespace Limelight\Plugins\Library\Romanji;

interface RomanjiConverterInterface
{
    /**
     * Convert string to romanji.
     *
     * @param string        $string
     * @param LimelightWord $word
     *
     * @return string
     */
    public function convert($string, $word);
}
