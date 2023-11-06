<?php

declare(strict_types=1);

namespace Limelight\Helpers;

use Limelight\Mecab\Node;
use Limelight\Config\Config;
use Limelight\Plugins\Plugin;
use Limelight\Classes\Collection;
use Limelight\Exceptions\PluginNotFoundException;

trait PluginHelper
{
    /**
     * Get data from pluginData.
     *
     * @param string $type   romaji, furigana
     * @param string $target self, child
     *
     * @throws PluginNotFoundException
     *
     * @return static
     */
    protected function getPluginData(string $type, string $target = 'child')
    {
        $type = ucfirst($type);

        if (is_null($this->pluginData)) {
            return null;
        }
        if (!isset($this->pluginData[$type])) {
            throw new PluginNotFoundException(
                "Plugin data for {$type} can not be found. Is the {$type} plugin registered in config?"
            );
        }

        if ($this instanceof Collection && $target === 'child') {
            return $this->pluck('pluginData')->pluck($type);
        }

        return $this->pluginData[$type];
    }

    /**
     * Run all registered plugins.
     */
    protected function runPlugins(string $text, ?Node $node, array $tokens, array $words, array $pluginWhiteList = []): array
    {
        $pluginResults = [];

        $config = Config::getInstance();

        $allPlugins = $config->getPlugins();

        foreach ($allPlugins as $plugin => $namespace) {
            if ($this->isWhiteListed($plugin, $pluginWhiteList)) {
                $this->validatePlugin($namespace);

                $pluginClass = new $namespace($text, $node, $tokens, $words);

                $pluginResults[$plugin] = $pluginClass->handle($pluginClass);
            }
        }

        return $pluginResults;
    }

    /**
     * Whitelist is empty or plugin is in white list.
     */
    private function isWhiteListed(string $plugin, array $pluginWhiteList): bool
    {
        if (empty($pluginWhiteList)) {
            return true;
        }

        array_map(static fn (string $value) => ucfirst($value), $pluginWhiteList);

        return in_array($plugin, $pluginWhiteList, true);
    }

    /**
     * Validate plugin class exists.
     *
     * @throws PluginNotFoundException
     */
    private function validatePlugin(string $namespace): void
    {
        if (!class_exists($namespace)) {
            throw new PluginNotFoundException("Plugin {$namespace} not found.");
        }
    }
}
