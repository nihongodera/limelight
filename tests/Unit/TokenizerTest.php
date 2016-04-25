<?php

namespace Limelight\tests\Unit;

use Limelight\Config\Config;
use Limelight\Tests\TestCase;
use Limelight\Parse\Tokenizer;

class TokenizerTest extends TestCase
{
    /**
     * @var Limelight\Mecab\Mecab
     */
    protected static $mecab;

    /**
     * Set static tokenizer on object.
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        
        $config = Config::getInstance();

        self::$mecab = $config->make('Limelight\Mecab\Mecab');
    }

    /**
     * @test
     */
    public function it_can_be_instantiated()
    {
        $tokenizer = new Tokenizer();

        $this->assertInstanceOf('Limelight\Parse\Tokenizer', $tokenizer);
    }

    /**
     * @test
     */
    public function it_makes_tokens_for_single_token_string()
    {
        $tokenizer = new Tokenizer();

        $node = self::$mecab->parseToNode('大丈夫');

        $tokens = $tokenizer->makeTokens($node);

        $this->assertCount(1, $tokens);

        $this->assertEquals('大丈夫', $tokens[0]['literal']);

        $this->assertEquals('meishi', $tokens[0]['partOfSpeech1']);

        $tokens = [];
    }

    /**
     * @test
     */
    public function it_makes_tokens_for_multi_token_string()
    {
        $tokenizer = new Tokenizer();

        $node = self::$mecab->parseToNode('行きたいです');

        $tokens = $tokenizer->makeTokens($node);

        $this->assertEquals('行き', $tokens[0]['literal']);

        $this->assertEquals('doushi', $tokens[0]['partOfSpeech1']);

        $this->assertEquals('たい', $tokens[1]['literal']);

        $this->assertEquals('jodoushi', $tokens[1]['partOfSpeech1']);

        $this->assertEquals('です', $tokens[2]['literal']);

        $this->assertEquals('jodoushi', $tokens[2]['partOfSpeech1']);
    }
}
