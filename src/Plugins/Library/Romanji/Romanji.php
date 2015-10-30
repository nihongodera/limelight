<?php

namespace Limelight\Plugins\Library\Romanji;

use Limelight\Limelight;
use Limelight\Plugins\Plugin;
use Limelight\Plugins\Library\Romanji\Styles\Hepburn;

class Romanji extends Plugin
{
    /**
     * Run the plugin.
     *
     * @return mixed
     */
    public function handle()
    {
        $options = $this->config->get('Romanji');

        $styleClass = 'Limelight\\Plugins\\Library\\Romanji\\Styles\\' . ucfirst($this->underscoreToCamelCase($options['style']));

        $style = new $styleClass();

        $romanjiString = '';

        foreach ($this->words as $word) {
            $hiraganaWord = mb_convert_kana($word->reading, 'c');

            $romanjiWord = $style->convert($hiraganaWord, $word);

            $word->setPluginData('Romanji', $romanjiWord);

            if ($word->partOfSpeech !== 'symbol') {
                $romanjiString .= ' ';
            }

            $romanjiString .= $romanjiWord;
        }

        return ucfirst(trim($romanjiString));
    }

    /**
     * Make an underscored word camel-case.
     *
     * @param   string  $string
     *
     * @return  string
     */
    function underscoreToCamelCase($string)
    {
        $string = strtolower($string);

        $string = trim($string, '_');

        while (strpos($string, '_')) {
            $index = strpos($string, '_');

            $letter = strtoupper($string[$index + 1]);

            $string[$index + 1] = $letter;

            $string = substr_replace($string, '', $index, 1);
        }

        $string = str_replace('_', '', $string);

        return $string;
    }
}
