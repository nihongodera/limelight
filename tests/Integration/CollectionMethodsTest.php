<?php

namespace Limelight\tests\Integration;

use Limelight\Limelight;
use Limelight\Config\Config;
use Limelight\Tests\TestCase;
use Limelight\Classes\LimelightResults;

class CollectionMethodsTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    protected static $limelight;

    /**
     * Set static limelight on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();
    }

    /**
     * count()
     */

    /**
     * @test
     */
    public function count_returns_count()
    {
        $this->assertEquals(4, $this->getResults()->count());
    }

    /**
     * filter()
     */

    /**
     * @test
     */
    public function filter_with_callback_returns_filtered_limelightresults_object()
    {
        $answer = $this->getResults()->filter(function ($item, $key) {
            return $item->get() !== '音楽';
        });

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertCount(3, $answer->all());
    }

    /**
     * @test
     */
    public function filter_with_null_returns_filtered_limelightresults_object()
    {
        $results = $this->getResults();

        $results->push([]);

        $this->assertCount(5, $results->all());

        $answer = $results->filter();

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertCount(4, $answer->all());
    }

    /**
     * first()
     */
    
    /**
     * @test
     */
    public function first_gets_first_word()
    {
        $first = $this->getResults()->first();

        $this->assertInstanceOf('Limelight\Classes\LimelightWord', $first);

        $this->assertEquals('音楽', $first->word());
    }

    /**
     * @test
     */
    public function first_gets_first_word_to_pass_truth_test()
    {
        $first = $this->getResults()->first(function ($item, $key) {
            return $item->word() === 'を';
        });

        $this->assertInstanceOf('Limelight\Classes\LimelightWord', $first);

        $this->assertEquals('を', $first->word());
    }

    /**
     * implode()
     */
    
    /**
     * @test
     */
    public function implode_implodes_and_returns_string()
    {
        $answer = $this->getResults()->implode('word');

        $this->assertEquals('音楽を聴きます。', $answer);
    }

    /**
     * isEmpty()
     */
    
    /**
     * @test
     */
    public function isempty_returns_false_if_not_empty()
    {
        $empty = $this->getResults()->isEmpty();

        $this->assertFalse($empty);
    }

    /**
     * @test
     */
    public function isempty_returns_true_if_empty()
    {
        $empty = self::$limelight->parse('')->isEmpty();

        $this->assertTrue($empty);
    }

    /**
     * map()
     */

    /**
     * @test
     */
    public function map_returns_mapped_limelightresults_object()
    {

        $answer = $this->getResults()->map(function ($item, $key) {
            return $item->get();
        });

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertEquals(['音楽', 'を', '聴きます', '。'], $answer->all());
    }

    /**
     * pluck()
     */
    
    /**
     * @test
     */
    public function pluck_plucks_values_for_key()
    {
        $words = $this->getResults()->pluck('word');

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $words);

        $this->assertEquals(['音楽', 'を', '聴きます', '。'], $words->all());
    }

    /**
     * push()
     */
    
    /**
     * @test
     */
    public function push_pushes_new_item_onto_limelightresults()
    {
        $results = $this->getResults();

        $this->assertCount(4, $results->all());

        $results->push(['test']);

        $this->assertCount(5, $results->all());
    }

    /**
     * Parse test phrase and return LimelightResults.
     *
     * @return LimelightResults
     */
    protected function getResults()
    {
        return self::$limelight->parse('音楽を聴きます。');
    }
}
