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
     * except()
     */
    
    /**
     * @test
     */
    public function except_returns_limelightresults_without_items()
    {
        $answer = $this->getResults()->except(0);

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertEquals(['を', '聴きます', '。'], $answer->pluck('word')->all());
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
        $results = $this->getResults()->push([]);

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
     * forget()
     */
    
    /**
     * @test
     */
    public function forget_forgets_item()
    {
        $answer = $this->getResults()->forget(1);

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertEquals(['音楽', '聴きます', '。'], $answer->pluck('word')->all());
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
     * last()
     */
    
    /**
     * @test
     */
    public function last_gets_last_word()
    {
        $last = $this->getResults()->last();

        $this->assertInstanceOf('Limelight\Classes\LimelightWord', $last);

        $this->assertEquals('。', $last->word());
    }

    /**
     * @test
     */
    public function last_gets_last_word_to_pass_truth_test()
    {
        $first = $this->getResults()->last(function ($item, $key) {
            return $item->word() === 'を';
        });

        $this->assertInstanceOf('Limelight\Classes\LimelightWord', $first);

        $this->assertEquals('を', $first->word());
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
     * merge()
     */
    
    /**
     * @test
     */
    public function merge_merges_two_collections()
    {
        $results1 = $this->getResults();

        $results2 = self::$limelight->parse('学校に行きます。');

        $merged = $results1->merge($results2);

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $merged);

        $this->assertEquals(['音楽', 'を', '聴きます', '。', '学校', 'に', '行きます', '。'], $merged->pluck('word')->all());
    }

    /**
     * only()
     */
    
    /**
     * @test
     */
    public function only_gets_values_only_for_specified_keys()
    {
        $answer = $this->getResults()->only(2);

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertEquals('聴きます', $answer->first()->word);
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
     * pop()
     */
    
    /**
     * @test
     */
    public function pop_pops()
    {
        $answer = $this->getResults()->pop();

        $this->assertInstanceOf('Limelight\Classes\LimelightWord', $answer);

        $this->assertEquals('。', $answer->word);
    }

    /**
     * prepend()
     */
    
    /**
     * @test
     */
    public function prepend_puts_item_at_front_of_items()
    {
        $word = $this->getResults()->first();

        $answer = $this->getResults()->prepend($word);

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertEquals(['音楽', '音楽', 'を', '聴きます', '。'], $answer->pluck('word')->all());
    }

    /**
     * pull()
     */
    
    /**
     * @test
     */
    public function pull_removes_word_by_key()
    {
        $results = $this->getResults();

        $answer = $results->pull(0);

        $this->assertInstanceOf('Limelight\Classes\LimelightWord', $answer);

        $this->assertEquals('音楽', $answer->word);

        $this->assertEquals(['を', '聴きます', '。'], $results->pluck('word')->all());
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
     * shift()
     */
    
    /**
     * @test
     */
    public function shift_removes_and_returns_first_item()
    {
        $results = $this->getResults();

        $answer = $results->shift();

        $this->assertEquals('音楽', $answer->word);

        $this->assertEquals(['を', '聴きます', '。'], $results->pluck('word')->all());
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            return $value instanceof Arrayable ? $value->toArray() : $value;
        }, $this->words);
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
