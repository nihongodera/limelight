<?php

namespace Limelight\tests\Integration;

use Limelight\Config\Config;
use Limelight\Tests\TestCase;
use Limelight\Classes\LimelightResults;

class CollectionMethodsTest extends TestCase
{
    /**
     * chunk()
     */
    
    /**
     * @test
     */
    public function chunk_chunks_items()
    {
        $results = $this->getResults()->merge($this->getResults());

        $chunks = $results->chunk(3);

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $chunks);

        $chunk1 = $chunks->pull(0);

        $chunk2 = $chunks->pull(1);

        $chunk3 = $chunks->pull(2);

        $this->assertEquals(['音楽', 'を', '聴きます'], $chunk1->pluck('word')->all());

        $this->assertEquals(['。', '音楽', 'を'], $chunk2->pluck('word')->all());

        $this->assertEquals(['聴きます', '。'], $chunk3->pluck('word')->all());
    }

    /**
     * convert()
     */
    
    /**
     * @test
     */
    public function convert_converts_array_to_format()
    {
        $readings = $this->getResults()->pluck('reading')->convert('hiragana')->flatten();

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $readings);

        $this->assertEquals(['おんがく', 'を', 'ききます', '。'], $readings->all());
    }

    /**
     * @test
     */
    public function convert_converts_limelightword_to_format()
    {
        $converted = $this->getResults()->only(0)->convert('hiragana');

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $converted);

        $word = $converted->first();

        $this->assertEquals('おんがく', $word->reading);

        $this->assertEquals('おんがく', $word->pronunciation);
    }

    /**
     * @test
     */
    public function convert_converts_nested_arrays_to_format()
    {
        $converted = $this->getResults()->only(0)->convert('katakana');

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $converted);

        $word = $converted->first();

        $this->assertEquals('<ruby><rb>音楽</rb><rp>(</rp><rt>オンガク</rt><rp>)</rp></ruby>', $word->pluginData['Furigana']);
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
     * diff()
     */
    
    /**
     * @test
     */
    public function diff_finds_items_that_are_different()
    {
        $answer = $this->getResults()->diff($this->getResults()->forget(0));

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertEquals(['音楽'], $answer->pluck('word')->all());
    }

    /**
     * every()
     */
    
    /**
     * @test
     */
    public function every_returns_every_nth_item()
    {
        $answer = $this->getResults()->every(2);

        $this->assertEquals(['音楽', '聴きます'], $answer->pluck('word')->all());
    }

    /**
     * @test
     */
    public function every_returns_every_nth_item_with_offset()
    {
        $answer = $this->getResults()->every(2, 1);

        $this->assertEquals(['を', '。'], $answer->pluck('word')->all());
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
     * flatten()
     */
    
    /**
     * @test
     */
    public function flatten_flattens_item()
    {
        $answer = $this->getResults()->pluck('pluginData')->flatten();

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertEquals([
            '<ruby><rb>音楽</rb><rp>(</rp><rt>おんがく</rt><rp>)</rp></ruby>',
            'ongaku',
            'を',
            'o',
            '<ruby><rb>聴</rb><rp>(</rp><rt>き</rt><rp>)</rp></ruby>きます',
            'kikimasu',
            '。',
            '.'
        ], $answer->all());
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
     * groupBy()
     */
    
    /**
     * @test
     */
    public function groupby_groups_items_by_key()
    {
        $answer = $this->getResults()->merge($this->getResults())->groupBy('partOfSpeech');

        $nouns = $answer->pull('noun');

        $postpositions = $answer->pull('postposition');

        $verbs = $answer->pull('verb');

        $symbols = $answer->pull('symbol');

        $this->assertEquals(['音楽', '音楽'], $nouns->pluck('word')->all());

        $this->assertEquals(['を', 'を'], $postpositions->pluck('word')->all());

        $this->assertEquals(['聴きます', '聴きます'], $verbs->pluck('word')->all());

        $this->assertEquals(['。', '。'], $symbols->pluck('word')->all());
    }

    /**
     * @test
     */
    public function groupby_groups_items_using_callback()
    {
        $answer = $this->getResults()->merge($this->getResults())->groupBy(function ($item, $key) {
            return substr($item->partOfSpeech, 0, 1);
        });

        $nouns = $answer->pull('n');

        $postpositions = $answer->pull('p');

        $verbs = $answer->pull('v');

        $symbols = $answer->pull('s');

        $this->assertEquals(['音楽', '音楽'], $nouns->pluck('word')->all());

        $this->assertEquals(['を', 'を'], $postpositions->pluck('word')->all());

        $this->assertEquals(['聴きます', '聴きます'], $verbs->pluck('word')->all());

        $this->assertEquals(['。', '。'], $symbols->pluck('word')->all());
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
     * intersect()
     */
    
    /**
     * @test
     */
    public function intersect_intersects_collections()
    {
        $answer = $this->getResults()->intersect($this->getResults()->forget(1)->forget(2));

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertEquals(['音楽', '。'], $answer->pluck('word')->all());
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
     * keys()
     */
    
    /**
     * @test
     */
    public function keys_returns_keys()
    {
        $keys = $this->getResults()->keys();

        $this->assertEquals([0, 1, 2, 3], $keys->all());
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
        $last = $this->getResults()->last(function ($item, $key) {
            return $item->word() === 'を';
        });

        $this->assertInstanceOf('Limelight\Classes\LimelightWord', $last);

        $this->assertEquals('を', $last->word());
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
     * reject()
     */
    
    /**
     * @test
     */
    public function reject_rejects_items_that_return_true_in_callback()
    {
        $answer = $this->getResults()->reject(function ($value, $key) {
            return $value->partOfSpeech !== 'verb';
        });

        $this->assertEquals(['聴きます'], $answer->pluck('word')->all());
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
     * slice()
     */
    
    /**
     * @test
     */
    public function slice_slices_array_at_given_index()
    {
        $results = $this->getResults();

        $answer = $results->slice(2);

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertEquals(['聴きます', '。'], $answer->pluck('word')->all());

        $this->assertEquals(['音楽', 'を', '聴きます', '。'], $results->pluck('word')->all());
    }

    /**
     * @test
     */
    public function slice_limits_size_of_return()
    {
        $results = $this->getResults();

        $answer = $results->slice(2, 1);

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertEquals(['聴きます'], $answer->pluck('word')->all());
    }

    /**
     * splice()
     */
    
    /**
     * @test
     */
    public function splice_splices_at_given_index()
    {
        $results = $this->getResults();

        $answer = $results->splice(2);

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertEquals(['音楽', 'を'], $results->pluck('word')->all());

        $this->assertEquals(['聴きます', '。'], $answer->pluck('word')->all());
    }

    /**
     * @test
     */
    public function splice_limits_size_of_return()
    {
        $results = $this->getResults()->merge($this->getResults());

        $answer = $results->splice(2, 2);

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertEquals(['音楽', 'を', '音楽', 'を', '聴きます', '。'], $results->pluck('word')->all());

        $this->assertEquals(['聴きます', '。'], $answer->pluck('word')->all());
    }

    /**
     * @test
     */
    public function splice_replaces_items()
    {
        $results = $this->getResults();

         $answer = $results->splice(2, 1, $this->getResults()->all());

         $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

         $this->assertEquals(['音楽', 'を', '音楽', 'を', '聴きます', '。', '。'], $results->pluck('word')->all());
    }

    /**
     * take()
     */

    /**
     * @test
     */
    public function take_takes_items_from_the_front()
    {
        $answer = $this->getResults()->take(2);

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertEquals(['音楽', 'を'], $answer->pluck('word')->all());
    }

    /**
     * @test
     */
    public function take_takes_items_from_the_back()
    {
        $answer = $this->getResults()->take(-2);

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertEquals(['聴きます', '。'], $answer->pluck('word')->all());
    }

    /**
     * toArray()
     */
    
    /**
     * @test
     */
    public function toarray_returns_array_with_public_properties()
    {
        $answer = $this->getResults()->toArray();

        $this->assertTrue(is_array($answer));

        $this->assertArrayHasKey('rawMecab', $answer[0]);

        $this->assertArrayHasKey('word', $answer[0]);

        $this->assertArrayHasKey('lemma', $answer[0]);

        $this->assertArrayHasKey('reading', $answer[0]);

        $this->assertArrayHasKey('pronunciation', $answer[0]);

        $this->assertArrayHasKey('partOfSpeech', $answer[0]);

        $this->assertArrayHasKey('grammar', $answer[0]);

        $this->assertArrayHasKey('parsed', $answer[0]);

        $this->assertArrayHasKey('pluginData', $answer[0]);
    }

    /**
     * toJson()
     */
    
    /**
     * @test
     */
    public function tojson_return_json_with_public_properties()
    {
        $json = $this->getResults()->toJson();

        $this->assertTrue(is_string($json));

        $array = json_decode($json);

        $this->assertTrue(is_array($array));

        $this->assertObjectHasAttribute('rawMecab', $array[0]);

        $this->assertObjectHasAttribute('word', $array[0]);

        $this->assertObjectHasAttribute('lemma', $array[0]);

        $this->assertObjectHasAttribute('reading', $array[0]);

        $this->assertObjectHasAttribute('pronunciation', $array[0]);

        $this->assertObjectHasAttribute('partOfSpeech', $array[0]);

        $this->assertObjectHasAttribute('grammar', $array[0]);

        $this->assertObjectHasAttribute('parsed', $array[0]);

        $this->assertObjectHasAttribute('pluginData', $array[0]);
    }

    /**
     * transform()
     */
    
    /**
     * @test
     */
    public function transform_transforms_items()
    {
        $results = $this->getResults();

        $results->transform(function ($item, $key) {
            return $item->word;
        });

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $results);

        $this->assertEquals(['音楽', 'を', '聴きます', '。'], $results->all());
    }

    /**
     * unique()
     */
    
    /**
     * @test
     */
    public function unique_returns_unique_items()
    {
        $results = $this->getResults()->merge($this->getResults());

        $this->assertEquals(['音楽', 'を', '聴きます', '。', '音楽', 'を', '聴きます', '。'], $results->pluck('word')->all());

        $unique = $results->unique();

        $this->assertEquals(['音楽', 'を', '聴きます', '。'], $unique->pluck('word')->all());
    }

    /**
     * @test
     */
    public function unique_return_unique_items_for_given_key()
    {
        $results = $this->getResults()->merge(self::$limelight->parse('行く'));

        $this->assertEquals(['音楽', 'を', '聴きます', '。', '行く'], $results->pluck('word')->all());

        $unique = $results->unique('partOfSpeech');

        $this->assertEquals(['音楽', 'を', '聴きます', '。'], $unique->pluck('word')->all());
    }

    /**
     * @test
     */
    public function unique_returns_unique_for_callback()
    {
        $results = self::$limelight->parse('行く 行きます 行った 帰る 買う');

        $this->assertEquals(['行く', '行きます', '行った', '帰る', '買う'], $results->pluck('word')->all());

        $unique = $results->unique(function ($item) {
            return $item->lemma;
        });

        $this->assertEquals(['行く', '行った', '帰る', '買う'], $unique->pluck('word')->all());
    }

    /**
     * values()
     */

    /**
     * @test
     */
    public function values_resets_values()
    {
        $modified = $this->getResults()->forget(1);

        $this->assertEquals([0, 2, 3], $modified->keys()->all());

        $reset = $modified->values();

        $this->assertEquals([0, 1, 2], $reset->keys()->all());
    }

    /**
     * where()
     */

    /**
     * @test
     */
    public function where_finds_items_meeting_conditions()
    {
        $answer = $this->getResults()->where('word', '音楽');

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $this->assertEquals(['音楽'], $answer->pluck('word')->all());
    }

    /**
     * zip()
     */
    
    /**
     * @test
     */
    public function zip_zips_collections_together()
    {
        $answer = $this->getResults()->zip([1, 2, 3, 4]);

        $this->assertInstanceOf('Limelight\Classes\LimelightResults', $answer);

        $all = $answer->all();

        $this->assertEquals(1, $all[0][1]);

        $this->assertEquals(2, $all[1][1]);

        $this->assertEquals(3, $all[2][1]);

        $this->assertEquals(4, $all[3][1]);
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
