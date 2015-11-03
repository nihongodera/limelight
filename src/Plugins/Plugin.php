<?php

namespace Limelight\Plugins;

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
    }

    /**
     * Run the plugin.
     *
     * @return mixed
     */
    abstract public function handle();
}
