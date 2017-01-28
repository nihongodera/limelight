<?php

namespace Limelight\Plugins\Library\Romaji;

use Limelight\Config\Config;
use Limelight\Plugins\Plugin;
use Limelight\Exceptions\PluginErrorException;

class Romaji extends Plugin
{
    /**
     * Run the plugin.
     *
     * @return mixed
     */
    public function handle()
    {
        $style = $this->makeStyleClass();

        $romajiString = '';

        foreach ($this->words as $word) {
            $spaces = true;

            $hiraganaWord = mb_convert_kana($word->reading, 'c');

            $romajiWord = $style->handle($hiraganaWord, $word);

            $word->setPluginData('Romaji', $romajiWord);

            if ($word->partOfSpeech !== 'symbol') {
                $romajiString .= ' ';
            }

            $romajiString .= $romajiWord;
        }

        $romajiString = trim($romajiString);

        return $this->uppercaseFirst($romajiString);
    }

    /**
     * Make decorator class from config value.
     *
     * @throws PluginErrorException
     *
     * @return RomajiConverter
     */
    private function makeStyleClass()
    {
        $config = Config::getInstance();

        $options = $config->get('Romaji');

        $style = $this->underscoreToCamelCase($options['style']);

        $styleClass = 'Limelight\\Plugins\\Library\\Romaji\\Styles\\'.ucfirst($style);

        if (class_exists($styleClass)) {
            return new $styleClass();
        }

        throw new PluginErrorException(
            "Style {$style} does not exist.  Check config.php file."
        );
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
