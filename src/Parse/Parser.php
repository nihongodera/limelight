<?php

namespace Limelight\Parse;

use Limelight\Mecab\Mecab;
use Limelight\Config\Config;
use Limelight\Plugins\Plugin;
use Limelight\Classes\LimelightResults;
use Limelight\Exceptions\LimelightPluginErrorException;

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
     * @param Mecab  $mecab
     * @param string $text
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
     *
     * @return [type] [description]
     */
    public function handle($text)
    {
        $node = $this->mecab->parseToNode($text);

        $tokens = $this->tokenizer->makeTokens($node);

        $words = $this->tokenParser->parseTokens($tokens);

        $pluginResults = $this->runPlugins($text, $node, $tokens, $words);

        return new LimelightResults($text, $words, $pluginResults);
    }

    /**
     * Run all registered plugins.
     *
     * @param string $text
     * @param Node   $node
     * @param array  $tokens
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
            throw new LimelightPluginErrorException("Plugin {$namespace} not found.");
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
