<?php

namespace Limelight\Parse;

use Limelight\Mecab\Mecab;
use Limelight\Config\Config;
use Limelight\Plugins\Plugin;
use Limelight\Classes\LimelightResults;
use Limelight\Exceptions\PluginNotFoundException;

class Parser
{
    /**
     * @var implements Limelight\Mecab\Mecab
     */
    private $mecab;

    /**
     * @var Limelight\Parse\Tokenizer
     */
    private $tokenizer;

    /**
     * @var Limelight\Parse\TokenParser
     */
    private $tokenParser;

    /**
     * Construct.
     *
     * @param Mecab       $mecab
     * @param Tokenizer   $tokenizer
     * @param TokenParser $tokenParser
     */
    public function __construct(Mecab $mecab, Tokenizer $tokenizer, TokenParser $tokenParser)
    {
        $this->mecab = $mecab;
        $this->tokenizer = $tokenizer;
        $this->tokenParser = $tokenParser;
    }

    /**
     * Handle the parse for given text.
     *
     * @param string $text
     * @param bool   $runPlugins
     *
     * @return LimelightResults
     */
    public function handle($text, $runPlugins)
    {
        $node = $this->mecab->parseToNode($text);

        $tokens = $this->tokenizer->makeTokens($node);

        $words = $this->tokenParser->parseTokens($tokens);

        $pluginResults = ($runPlugins ? $this->runPlugins($text, $node, $tokens, $words) : null);

        return new LimelightResults($text, $words, $pluginResults);
    }

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
    private function runPlugins($text, $node, $tokens, $words)
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
