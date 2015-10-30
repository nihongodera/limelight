<?php

<?php

namespace Limelight\Plugins\Library\Romanji\Styles;

use Limelight\Plugins\Library\Romanji\RomanjiStyle;

class HepburnModified extends RomanjiStyle
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
        $this->conversions = include dirname(__DIR__).'/Lib/HepburnModified.php';
    }

    /**
     * Convert string to romanji.
     *
     * @param string $string
     * @param LimelightWord $word
     *
     * @return string
     */
    public function convert($string, $word)
    {
        $this->eat = 0;

        $this->combineVowels = false;

        $characters = preg_split('//u', $hiraganaString, -1, PREG_SPLIT_NO_EMPTY);

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

            if ($this->conversions[$finalChar] === substr($results, -1)) {
                $firstHalf = mb_substr($hiraganaString, 0, $index);

                $lastHalf = mb_substr($hiraganaString, $index);

                // $limelight = new Limelight();

                // $firstWord = $limelight->parse($firstHalf)->getByIndex(0);

                // $lastWord = $limelight->parse($lastHalf)->getByIndex(0);

                // if (is_null($firstWord->reading) && is_null($lastWord->reading)) {
                //     $this->combineVowels = true;


                // }
            }

            $results .= $this->conversions[$finalChar];
        }

        return $results;
    }
}
