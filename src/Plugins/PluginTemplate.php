<?php

namespace Limelight\Plugins\Plugins;

use Limelight\Plugins\Plugin;

class PluginTemplate extends Plugin
{
    /**
     * Construct.
     *
     * @param string $text
     * @param Node   $node
     * @param array  $tokens
     * @param array  $words
     */
    public function __construct($text, $node, $tokens, $words)
    {
        // Construct what you need or delete this

        parent::__construct($text, $node, $tokens, $words);
    }

    /**
     * Run the plugin.
     *
     * @return mixed
     */
    public function handle()
    {
        // Your plugin logic

        // Example code:

        // Data array
        $allData = [];

        // Loop through words
        foreach ($this->words as $word) {
            // Access config.php options
            $config = Config::getInstance();

            $options = $config->get('PluginName');

            // Do something cool
            $data = $this->yourMethod($word, $options);

            // Set data on $word object
            $word->setPluginData('PluginName', $data);

            // Set data on allData array
            $allData[$word->word] = $data;
        }

        // Return allData to set it on the LimelightResults object
        return $allData;
    }
}
