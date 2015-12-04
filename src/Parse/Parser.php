<?php

namespace Limelight\Parse;

use Limelight\Mecab\Mecab;
use Limelight\Helpers\PluginHelper;
use Limelight\Classes\LimelightResults;

class Parser
{
    use PluginHelper;

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
}
