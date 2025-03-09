<?php

declare(strict_types=1);

namespace Limelight\tests\Integration;

use Limelight\Classes\LimelightResults;
use Limelight\Classes\LimelightWord;
use Limelight\Tests\TestCase;

class CollectionMethodsTest extends TestCase
{
    /**
     * chunk()
     */

    public function testChunkChunksItems(): void
    {
        $results = $this->getResults()->merge($this->getResults());

        $chunks = $results->chunk(3);

        $this->assertInstanceOf(LimelightResults::class, $chunks);

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

    public function testConvertConvertsArrayToFormat(): void
    {
        $readings = $this->getResults()->pluck('reading')->convert('hiragana')->flatten();

        $this->assertInstanceOf(LimelightResults::class, $readings);

        $this->assertEquals(['おんがく', 'を', 'ききます', '。'], $readings->all());
    }

    public function testConvertConvertsLimelightwordToFormat(): void
    {
        $converted = $this->getResults()->only(0)->convert('hiragana');

        $this->assertInstanceOf(LimelightResults::class, $converted);

        $word = $converted->first();

        $this->assertEquals('おんがく', $word->reading);

        $this->assertEquals('おんがく', $word->pronunciation);
    }

    public function testConvertConvertsNestedArraysToFormat(): void
    {
        $converted = $this->getResults()->only(0)->convert('katakana');

        $this->assertInstanceOf(LimelightResults::class, $converted);

        $word = $converted->first();

        $this->assertEquals('<ruby><rb>音楽</rb><rp>(</rp><rt>オンガク</rt><rp>)</rp></ruby>', $word->pluginData['Furigana']);
    }

    /**
     * count()
     */

    public function testCountReturnsCount(): void
    {
        $this->assertEquals(4, $this->getResults()->count());
    }

    /**
     * diff()
     */

    public function testDiffFindsItemsThatAreDifferent(): void
    {
        $answer = $this->getResults()->diff($this->getResults()->forget(0));

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertEquals(['音楽'], $answer->pluck('word')->all());
    }

    /**
     * nth()
     */

    public function testEveryReturnsEveryNthItem(): void
    {
        $answer = $this->getResults()->nth(2);

        $this->assertEquals(['音楽', '聴きます'], $answer->pluck('word')->all());
    }

    public function testEveryReturnsEveryNthItemWithOffset(): void
    {
        $answer = $this->getResults()->nth(2, 1);

        $this->assertEquals(['を', '。'], $answer->pluck('word')->all());
    }

    /**
     * except()
     */

    public function testExceptReturnsLimelightresultsWithoutItems(): void
    {
        $answer = $this->getResults()->except(0);

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertEquals(['を', '聴きます', '。'], $answer->pluck('word')->all());
    }

    /**
     * filter()
     */

    public function testFilterWithCallbackReturnsFilteredLimelightResultsObject(): void
    {
        $answer = $this->getResults()->filter(function ($item, $key) {
            return $item->get() !== '音楽';
        });

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertCount(3, $answer->all());
    }

    public function testFilterWithNullReturnsFilteredLimelightResultsObject(): void
    {
        $results = $this->getResults()->push([]);

        $this->assertCount(5, $results->all());

        $answer = $results->filter();

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertCount(4, $answer->all());
    }

    /**
     * first()
     */

    public function testFirstGetsFirstWord(): void
    {
        $first = $this->getResults()->first();

        $this->assertInstanceOf(LimelightWord::class, $first);

        $this->assertEquals('音楽', $first->word());
    }

    public function testFirstGetsFirstWordToPassTruthTest(): void
    {
        $first = $this->getResults()->first(function ($item, $key) {
            return $item->word() === 'を';
        });

        $this->assertInstanceOf(LimelightWord::class, $first);

        $this->assertEquals('を', $first->word());
    }

    /**
     * flatten()
     */

    public function testFlattenFlattensItem(): void
    {
        $answer = $this->getResults()->pluck('pluginData')->flatten();

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertEquals([
            '<ruby><rb>音楽</rb><rp>(</rp><rt>おんがく</rt><rp>)</rp></ruby>',
            'ongaku',
            'を',
            'o',
            '<ruby><rb>聴</rb><rp>(</rp><rt>き</rt><rp>)</rp></ruby>きます',
            'kikimasu',
            '。',
            '.',
        ], $answer->all());
    }

    /**
     * forget()
     */

    public function testForgetForgetsItem(): void
    {
        $answer = $this->getResults()->forget(1);

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertEquals(['音楽', '聴きます', '。'], $answer->pluck('word')->all());
    }

    /**
     * groupBy()
     */

    public function testGroupByGroupsItemsByKey(): void
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

    public function testGroupByGroupsItemsUsingCallback(): void
    {
        $answer = $this->getResults()->merge($this->getResults())->groupBy(function ($item) {
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

    public function testImplodeImplodesAndReturnsString(): void
    {
        $answer = $this->getResults()->implode('word');

        $this->assertEquals('音楽を聴きます。', $answer);
    }

    /**
     * intersect()
     */

    public function testIntersectIntersectsCollections(): void
    {
        $answer = $this->getResults()->intersect($this->getResults()->forget(1)->forget(2));

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertEquals(['音楽', '。'], $answer->pluck('word')->all());
    }

    /**
     * isEmpty()
     */

    public function testIsEmptyReturnsFalseIfNotEmpty(): void
    {
        $empty = $this->getResults()->isEmpty();

        $this->assertFalse($empty);
    }

    public function testIsEmptyReturnsTrueIfEmpty(): void
    {
        $empty = self::$limelight->parse('')->isEmpty();

        $this->assertTrue($empty);
    }

    /**
     * keys()
     */

    public function testKeysReturnsKeys(): void
    {
        $keys = $this->getResults()->keys();

        $this->assertEquals([0, 1, 2, 3], $keys->all());
    }

    /**
     * last()
     */

    public function testLastGetsLastWord(): void
    {
        $last = $this->getResults()->last();

        $this->assertInstanceOf(LimelightWord::class, $last);

        $this->assertEquals('。', $last->word());
    }

    public function testLastGetsLastWordToPassTruthTest(): void
    {
        $last = $this->getResults()->last(function ($item, $key) {
            return $item->word() === 'を';
        });

        $this->assertInstanceOf(LimelightWord::class, $last);

        $this->assertEquals('を', $last->word());
    }

    /**
     * map()
     */

    public function testMapReturnsMappedLimelightResultsObject(): void
    {
        $answer = $this->getResults()->map(function ($item) {
            return $item->get();
        });

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertEquals(['音楽', 'を', '聴きます', '。'], $answer->all());
    }

    /**
     * merge()
     */

    public function testMergeMergesTwoCollections(): void
    {
        $results1 = $this->getResults();

        $results2 = self::$limelight->parse('学校に行きます。');

        $merged = $results1->merge($results2);

        $this->assertInstanceOf(LimelightResults::class, $merged);

        $this->assertEquals(['音楽', 'を', '聴きます', '。', '学校', 'に', '行きます', '。'], $merged->pluck('word')->all());
    }

    /**
     * only()
     */

    public function testOnlyGetsValuesOnlyForSpecifiedKeys(): void
    {
        $answer = $this->getResults()->only(2);

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertEquals('聴きます', $answer->first()->word);
    }

    /**
     * pluck()
     */

    public function testPluckPlucksValuesForKey(): void
    {
        $words = $this->getResults()->pluck('word');

        $this->assertInstanceOf(LimelightResults::class, $words);

        $this->assertEquals(['音楽', 'を', '聴きます', '。'], $words->all());
    }

    /**
     * pop()
     */

    public function testPopPops(): void
    {
        $answer = $this->getResults()->pop();

        $this->assertInstanceOf(LimelightWord::class, $answer);

        $this->assertEquals('。', $answer->word);
    }

    /**
     * prepend()
     */

    public function testPrependPutsItemAtFrontOfItems(): void
    {
        $word = $this->getResults()->first();

        $answer = $this->getResults()->prepend($word);

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertEquals(['音楽', '音楽', 'を', '聴きます', '。'], $answer->pluck('word')->all());
    }

    /**
     * pull()
     */

    public function testPullRemovesWordByKey(): void
    {
        $results = $this->getResults();

        $answer = $results->pull(0);

        $this->assertInstanceOf(LimelightWord::class, $answer);

        $this->assertEquals('音楽', $answer->word);

        $this->assertEquals(['を', '聴きます', '。'], $results->pluck('word')->all());
    }

    /**
     * push()
     */

    public function testPushPushesNewItemOntoLimelightResults(): void
    {
        $results = $this->getResults();

        $this->assertCount(4, $results->all());

        $results->push(['test']);

        $this->assertCount(5, $results->all());
    }

    /**
     * reject()
     */

    public function testRejectRejectsItemsThatReturnTrueInCallback(): void
    {
        $answer = $this->getResults()->reject(function ($value) {
            return $value->partOfSpeech !== 'verb';
        });

        $this->assertEquals(['聴きます'], $answer->pluck('word')->all());
    }

    /**
     * shift()
     */

    public function testShiftRemovesAndReturnsFirstItem(): void
    {
        $results = $this->getResults();

        $answer = $results->shift();

        $this->assertEquals('音楽', $answer->word);

        $this->assertEquals(['を', '聴きます', '。'], $results->pluck('word')->all());
    }

    /**
     * slice()
     */

    public function testSliceSlicesArrayAtGivenIndex(): void
    {
        $results = $this->getResults();

        $answer = $results->slice(2);

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertEquals(['聴きます', '。'], $answer->pluck('word')->all());

        $this->assertEquals(['音楽', 'を', '聴きます', '。'], $results->pluck('word')->all());
    }

    public function testSliceLimitsSizeOfReturn(): void
    {
        $results = $this->getResults();

        $answer = $results->slice(2, 1);

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertEquals(['聴きます'], $answer->pluck('word')->all());
    }

    /**
     * splice()
     */

    public function testSpliceSplicesAtGivenIndex(): void
    {
        $results = $this->getResults();

        $answer = $results->splice(2);

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertEquals(['音楽', 'を'], $results->pluck('word')->all());

        $this->assertEquals(['聴きます', '。'], $answer->pluck('word')->all());
    }

    public function testSpliceLimitsSizeOfReturn(): void
    {
        $results = $this->getResults()->merge($this->getResults());

        $answer = $results->splice(2, 2);

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertEquals(['音楽', 'を', '音楽', 'を', '聴きます', '。'], $results->pluck('word')->all());

        $this->assertEquals(['聴きます', '。'], $answer->pluck('word')->all());
    }

    public function testSpliceReplacesItems(): void
    {
        $results = $this->getResults();

        $answer = $results->splice(2, 1, $this->getResults()->all());

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertEquals(['音楽', 'を', '音楽', 'を', '聴きます', '。', '。'], $results->pluck('word')->all());
    }

    /**
     * take()
     */

    public function testTakeTakesItemsFromTheFront(): void
    {
        $answer = $this->getResults()->take(2);

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertEquals(['音楽', 'を'], $answer->pluck('word')->all());
    }

    public function testTakeTakesItemsFromTheBack(): void
    {
        $answer = $this->getResults()->take(-2);

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertEquals(['聴きます', '。'], $answer->pluck('word')->all());
    }

    /**
     * toArray()
     */

    public function testToArrayReturnsArrayWithPublicProperties(): void
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

    public function testToJsonReturnJsonWithPublicProperties(): void
    {
        $json = $this->getResults()->toJson();

        $this->assertTrue(is_string($json));

        $array = json_decode($json);

        $this->assertTrue(is_array($array));

        $this->assertIsObject($array[0]);

        $this->assertTrue(property_exists($array[0], 'rawMecab'));

        $this->assertTrue(property_exists($array[0], 'word'));

        $this->assertTrue(property_exists($array[0], 'lemma'));

        $this->assertTrue(property_exists($array[0], 'reading'));

        $this->assertTrue(property_exists($array[0], 'pronunciation'));

        $this->assertTrue(property_exists($array[0], 'partOfSpeech'));

        $this->assertTrue(property_exists($array[0], 'grammar'));

        $this->assertTrue(property_exists($array[0], 'parsed'));

        $this->assertTrue(property_exists($array[0], 'pluginData'));
    }

    /**
     * transform()
     */

    public function testTransformTransformsItems(): void
    {
        $results = $this->getResults();

        $results->transform(function ($item, $key) {
            return $item->word;
        });

        $this->assertInstanceOf(LimelightResults::class, $results);

        $this->assertEquals(['音楽', 'を', '聴きます', '。'], $results->all());
    }

    /**
     * unique()
     */

    public function testUniqueReturnsUniqueItems(): void
    {
        $results = $this->getResults()->merge($this->getResults());

        $this->assertEquals(['音楽', 'を', '聴きます', '。', '音楽', 'を', '聴きます', '。'], $results->pluck('word')->all());

        $unique = $results->unique();

        $this->assertEquals(['音楽', 'を', '聴きます', '。'], $unique->pluck('word')->all());
    }

    public function testUniqueReturnUniqueItemsForGivenKey(): void
    {
        $results = $this->getResults()->merge(self::$limelight->parse('行く'));

        $this->assertEquals(['音楽', 'を', '聴きます', '。', '行く'], $results->pluck('word')->all());

        $unique = $results->unique('partOfSpeech');

        $this->assertEquals(['音楽', 'を', '聴きます', '。'], $unique->pluck('word')->all());
    }

    public function testUniqueReturnsUniqueForCallback(): void
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

    public function testValuesResetsValues(): void
    {
        $modified = $this->getResults()->forget(1);

        $this->assertEquals([0, 2, 3], $modified->keys()->all());

        $reset = $modified->values();

        $this->assertEquals([0, 1, 2], $reset->keys()->all());
    }

    /**
     * where()
     */

    public function testWhereFindsItemsMeetingConditions(): void
    {
        $answer = $this->getResults()->where('word', '音楽');

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $this->assertEquals(['音楽'], $answer->pluck('word')->all());
    }

    /**
     * zip()
     */

    public function testZipZipsCollectionsTogether(): void
    {
        $answer = $this->getResults()->zip([1, 2, 3, 4]);

        $this->assertInstanceOf(LimelightResults::class, $answer);

        $all = $answer->all();

        $this->assertEquals(1, $all[0][1]);

        $this->assertEquals(2, $all[1][1]);

        $this->assertEquals(3, $all[2][1]);

        $this->assertEquals(4, $all[3][1]);
    }

    /**
     * Parse test phrase and return LimelightResults.
     */
    protected function getResults(): LimelightResults
    {
        return self::$limelight->parse('音楽を聴きます。');
    }
}
