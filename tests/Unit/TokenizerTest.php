<?php

declare(strict_types=1);

namespace Limelight\tests\Unit;

use Limelight\Mecab\Mecab;
use Limelight\Config\Config;
use Limelight\Tests\TestCase;
use Limelight\Parse\Tokenizer;

class TokenizerTest extends TestCase
{
    protected static Mecab $mecab;

    /**
     * Set static tokenizer on object.
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $config = Config::getInstance();

        self::$mecab = $config->make(Mecab::class);
    }

    /**
     * @test
     */
    public function it_can_be_instantiated(): void
    {
        $tokenizer = new Tokenizer();

        $this->assertInstanceOf(Tokenizer::class, $tokenizer);
    }

    /**
     * @test
     */
    public function it_makes_tokens_for_single_token_string(): void
    {
        $tokenizer = new Tokenizer();

        $node = self::$mecab->parseToNode('大丈夫');

        $tokens = $tokenizer->makeTokens($node);

        $this->assertCount(1, $tokens);

        $this->assertEquals('大丈夫', $tokens[0]['literal']);

        $this->assertEquals('meishi', $tokens[0]['partOfSpeech1']);
    }

    /**
     * @test
     */
    public function it_makes_tokens_for_multi_token_string(): void
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
