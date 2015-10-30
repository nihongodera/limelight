<?php

namespace Limelight\Plugins\Library\Romanji\Styles;

use Limelight\Plugins\Library\Romanji\RomanjiStyle;

class HepburnTraditional extends RomanjiStyle
{
    /**
     * Romanji library.
     *
     * @var array
     */
    protected $conversions;
    /**
     * Number of index values to eat.
     *
     * @var int
     */
    protected $eat;

    /**
     * Can be combined with other characters.
     *
     * @var array
     */
    protected $edible = [
        'ゃ',
        'ゅ',
        'ょ',
        'ぇ',
        'ぃ',
        'あ',
        'い',
        'う',
        'え',
        'お',
    ];

    /**
     * Construct.
     */
    public function __construct()
    {
        $this->conversions = include dirname(__DIR__).'/Lib/HepburnTraditional.php';
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
        $this->eat = 0;

        $characters = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);

        $count = count($characters);

        $results = '';

        for ($index = 0; $index < $count; ++$index) {
            $index = $index + $this->eat;

            if ($index >= $count) {
                break;
            }

            $this->eat = 0;

            $char = $characters[$index];

            $next = (isset($characters[$index + 1]) ?  $characters[$index + 1] : null);

            $nextNext = (isset($characters[$index + 2]) ?  $characters[$index + 2] : null);

            $finalChar = $this->findCombos($char, $next, $nextNext);

            if ($char === 'っ') {
                if ($this->canBeRomanji($next)) {
                    $nextRomanji = $this->conversions[$next];

                    $firstChar = preg_split('//u', $nextRomanji, -1, PREG_SPLIT_NO_EMPTY)[0];

                    $results .= ($firstChar !== 'c' ? $firstChar : 't');

                    continue;
                }
            }

            if ($this->canBeRomanji($finalChar)) {
                $results .= $this->conversions[$finalChar];
            } else {
                $results .= $finalChar;
            }
        }

        return $this->upperCaseNames($results, $word);
    }
}
