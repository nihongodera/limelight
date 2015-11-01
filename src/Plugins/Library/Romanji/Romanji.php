<?php

namespace Limelight\Plugins\Library\Romanji;

use Limelight\Limelight;
use Limelight\Plugins\Plugin;

class Romanji extends Plugin
{
    /**
     * Run the plugin.
     *
     * @return mixed
     */
    public function handle()
    {
        $decorator = $this->makeDecoratorClass();

        $romanjiString = '';

        foreach ($this->words as $word) {
            $hiraganaWord = mb_convert_kana($word->reading, 'c');

            $romanjiWord = $decorator->convert($hiraganaWord, $word);

            $word->setPluginData('Romanji', $romanjiWord);

            if ($word->partOfSpeech !== 'symbol') {
                $romanjiString .= ' ';
            }

            $romanjiString .= $romanjiWord;
        }

        $romanjiString = trim($romanjiString);

        return $this->uppercaseFirst($romanjiString);
    }

    /**
     * Make decorator class from config value.
     *
     * @return Limelight\Plugins\Library\Romanji\StyleDecorator
     */
    private function makeDecoratorClass()
    {
        $options = $this->config->get('Romanji');

        $style = $this->underscoreToCamelCase($options['style']);

        $decoratorClass = 'Limelight\\Plugins\\Library\\Romanji\\Styles\\'.ucfirst($style);

        if (class_exists($decoratorClass)) {
            $converter = new RomanjiConverter();

            return new $decoratorClass($converter);
        }

        throw new LimelightPluginErrorException("Style {$style} does not exist.  Check config.php file.");
    }

    /**
     * Make an underscored word camel-case.
     *
     * @param string $string
     *
     * @return string
     */
    public function underscoreToCamelCase($string)
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

    /**
     * Multibyte safe ucfirst.
     *
     * @param string $string
     *
     * @return string
     */
    public function uppercaseFirst($string)
    {
        $firstChar = mb_substr($string, 0, 1);

        $rest = mb_substr($string, 1);

        return mb_convert_case($firstChar, MB_CASE_UPPER, 'UTF-8').$rest;
    }
}
