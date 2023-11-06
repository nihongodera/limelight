<?php

declare(strict_types=1);

namespace Limelight\Plugins;

use Limelight\Mecab\Node;
use Limelight\Config\Config;

class PluginTemplate extends Plugin
{
    public function __construct(string $text, ?Node $node, array $tokens, array $words)
    {
        // Construct what you need or delete this

        parent::__construct($text, $node, $tokens, $words);
    }

    /**
     * Run the plugin.
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
