<?php

declare(strict_types=1);

namespace Limelight\Parse;

use Limelight\Mecab\Mecab;
use Limelight\Events\Dispatcher;
use Limelight\Helpers\PluginHelper;
use Limelight\Classes\LimelightResults;

class Parser
{
    use PluginHelper;

    private Mecab $mecab;

    private Tokenizer $tokenizer;

    private TokenParser $tokenParser;

    private Dispatcher $dispatcher;

    public function __construct(
        Mecab $mecab,
        Tokenizer $tokenizer,
        TokenParser $tokenParser,
        Dispatcher $dispatcher
    ) {
        $this->mecab = $mecab;
        $this->tokenizer = $tokenizer;
        $this->tokenParser = $tokenParser;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handle the parse for given text.
     */
    public function handle(string $text, bool $runPlugins): LimelightResults
    {
        $node = $this->mecab->parseToNode($text);

        $tokens = $this->tokenizer->makeTokens($node);

        $words = $this->tokenParser->parseTokens($tokens);

        if ($runPlugins) {
            $pluginResults = $this->runPlugins($text, $node, $tokens, $words);
        } else {
            $pluginResults = null;
        }

        $results = new LimelightResults($text, $words, $pluginResults);

        $this->dispatcher->fire('ParseWasSuccessful', $results);

        return $results;
    }
}
