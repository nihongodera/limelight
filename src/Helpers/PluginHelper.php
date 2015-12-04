<?php

namespace Limelight\Helpers;

use Limelight\Config\Config;
use Limelight\Plugins\Plugin;

trait PluginHelper
{
    /**
     * Run all registered plugins.
     *
     * @param string $text
     * @param Node   $node
     * @param array  $tokens
     * @param array  $words
     *
     * @return array
     */
    protected function runPlugins($text, $node, $tokens, $words)
    {
        $pluginResults = [];

        $config = Config::getInstance();

        $plugins = $config->getPlugins();

        foreach ($plugins as $plugin => $namespace) {
            $this->validatePlugin($namespace);

            $pluginClass = new $namespace($text, $node, $tokens, $words);

            $pluginResults[$plugin] = $this->firePlugin($pluginClass);
        }

        return $pluginResults;
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
