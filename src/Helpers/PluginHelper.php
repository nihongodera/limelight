<?php

namespace Limelight\Helpers;

use Limelight\Mecab\Node;
use Limelight\Config\Config;
use Limelight\Plugins\Plugin;
use Limelight\Exceptions\PluginNotFoundException;

trait PluginHelper
{
    /**
     * Run all registered plugins.
     *
     * @param string $text
     * @param Node/null   $node
     * @param array/null  $tokens
     * @param array/null  $words
     * @param array  $pluginWhiteList
     *
     * @return array
     */
    protected function runPlugins($text, $node, $tokens, $words, $pluginWhiteList = [])
    {
        $pluginResults = [];

        $config = Config::getInstance();

        $allPlugins = $config->getPlugins();

        foreach ($allPlugins as $plugin => $namespace) {
            if ($this->isWhiteListed($plugin, $pluginWhiteList)) {
                $this->validatePlugin($namespace);

                $pluginClass = new $namespace($text, $node, $tokens, $words);

                $pluginResults[$plugin] = $this->firePlugin($pluginClass);
            }
        }

        return $pluginResults;
    }

    /**
     * Whitelist is empty or plugin is in white list.
     *
     * @param string $plugin
     * @param array  $pluginWhiteList
     *
     * @return bool
     */
    private function isWhiteListed($plugin, array $pluginWhiteList)
    {
        if (empty($pluginWhiteList)) {
            return true;
        }

        array_map(function ($value) {
            return ucfirst($value);
        }, $pluginWhiteList);

        return in_array($plugin, $pluginWhiteList);
    }

    /**
     * Validate plugin class exists.
     *
     * @param string $namespace
     */
    private function validatePlugin($namespace)
    {
        if (!class_exists($namespace)) {
            throw new PluginNotFoundException("Plugin {$namespace} not found.");
        }
    }

    /**
     * Fire the plugin.
     *
     * @param Plugin $plugin
     *
     * @return mixed
     */
    private function firePlugin(Plugin $plugin)
    {
        return $plugin->handle();
    }
}
