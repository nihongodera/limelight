<?php

namespace Limelight\Helpers;

use Limelight\Config\Config;
use Limelight\Exceptions\PluginNotFoundException;

trait ResultsHelpers
{
    /**
     * Get plugin data from object.
     *
     * @param string $pluginName [The name of the plugin]
     *
     * @return mixed/bool
     */
    public function plugin($pluginName)
    {
        if (isset($this->pluginData[$pluginName])) {
            return $this->pluginData[$pluginName];
        }

        return false;
    }

    /**
     * Make sure plugin is registerd in config.php.
     *
     * @param string $plugin
     *
     * @return none/PluginNotFoundException
     */
    protected function checkPlugin($plugin)
    {
        $config = Config::getInstance();

        $pluginName = ucfirst($plugin);

        if (!in_array($pluginName, array_keys($config->getPlugins()))) {
            throw new PluginNotFoundException("Plugin {$pluginName} not found in config.php");
        }
    }
}
