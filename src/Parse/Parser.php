<?php

namespace Limelight\Parse;

use Limelight\Classes\LimelightResults;
use Limelight\Events\Dispatcher;
use Limelight\Helpers\PluginHelper;
use Limelight\Mecab\Mecab;

class Parser
{
    use PluginHelper;

    /**
     * @var Mecab
     */
    private $mecab;

    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * @var TokenParser
     */
    private $tokenParser;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Construct.
     *
     * @param Mecab $mecab
     * @param Tokenizer $tokenizer
     * @param TokenParser $tokenParser
     * @param Dispatcher $dispatcher
     */
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
     *
     * @param string $text
     * @param bool $runPlugins
     *
     * @return LimelightResults
     */
    public function handle($text, $runPlugins)
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
