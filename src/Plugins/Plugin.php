<?php

declare(strict_types=1);

namespace Limelight\Plugins;

use Limelight\Mecab\Node;
use Limelight\Classes\LimelightWord;

abstract class Plugin
{
    /**
     * The original, user input text.
     */
    protected string $text;

    /**
     * First node from mecab results.
     */
    protected ?Node $node;

    /**
     * Tokens derived from parsing nodes.
     */
    protected array $tokens;

    /**
     * Words derived from text.
     *
     * @var LimelightWord[]
     */
    protected array $words;

    public function __construct(string $text, ?Node $node, array $tokens, array $words)
    {
        $this->text = $text;
        $this->node = $node;
        $this->tokens = $tokens;
        $this->words = $words;
    }

    /**
     * Run the plugin.
     */
    abstract public function handle();
}
