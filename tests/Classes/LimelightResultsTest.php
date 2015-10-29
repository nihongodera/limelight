<?php

namespace Limelight\tests\Classes;

use Limelight\Limelight;
use Limelight\Tests\TestCase;
use Limelight\Classes\LimelightResults;

class LimelightResultsTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    protected static $limelight;

    /**
     * @var Limelight\Classes\LimelightResults
     */
    protected static $results;

    /**
     * Set static limelight on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();

        self::$results = self::$limelight->parse('音楽を聴きます。');
    }

    /**
     * It can be instantiated.
     *
     * @test
     */
    public function it_can_be_instantiated()
    {
        $results = new LimelightResults('test', ['item', 'another thing']);

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $results);
    }

    /**
     * It can get the original text off the object.
     *
     * @test
     */
    public function it_can_get_original_string()
    {
        $original = self::$results->getOriginal();

        $this->AssertEquals('音楽を聴きます。', $original);
    }

    /**
     * It can make a result string from words.
     *
     * @test
     */
    public function it_can_build_result_string()
    {
        $string = self::$results->getResultString();

        $this->AssertEquals('音楽を聴きます。', $string);
    }

    /**
     * It can get the all words array off the object.
     *
     * @test
     */
    public function it_can_get_all_words()
    {
        $words = self::$results->getAll();

        $this->AssertCount(4, $words);
    }

    /**
     * It can get words from generator.
     *
     * @test
     */
    public function it_can_get_words_from_generator()
    {
        $results = self::$results;

        $words = $results->getAll();

        $count = 0;

        foreach ($results->getNext() as $word) {
            $this->AssertEquals($words[$count], $word);

            $count += 1;
        }
    }

    /**
     * It can get a single word by string.
     *
     * @test
     */
    public function it_can_get_word_by_string()
    {
        $word = self::$results->getByWord('聴きます');

        $this->AssertEquals('聴きます', $word->word()->get());
    }

    /**
     * It can get a single word by index.
     *
     * @test
     */
    public function it_can_get_word_by_index()
    {
        $word = self::$results->getByIndex(2);

        $this->AssertEquals('聴きます', $word->word()->get());
    }
}
