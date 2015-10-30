<?php

namespace Limelight\Plugins;

use Limelight\Config\Config;

abstract class Plugin
{
    /**
     * The original, user inputed text.
     *
     * @var string
     */
    protected $text;

    /**
     * First node from mecab results.
     *
     * @var Limelight\Mecab\Node
     */
    protected $node;

    /**
     * Tokens derived from parsing nodes.
     *
     * @var array
     */
    protected $tokens;

    /**
     * Words derived from text.
     *
     * @var array
     */
    protected $words;

    /**
     * Config access.
     *
     * @var Limelight\Config\Config
     */
    protected $config;

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
        $this->text = $text;
        $this->node = $node;
        $this->tokens = $tokens;
        $this->words = $words;
        $this->config = Config::getInstance();
    }

    /**
     * Run the plugin.
     *
     * @return mixed
     */
    abstract public function handle();
}
