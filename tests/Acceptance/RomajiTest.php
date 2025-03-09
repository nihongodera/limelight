<?php

declare(strict_types=1);

namespace Limelight\tests\Acceptance;

use Limelight\Config\Config;
use Limelight\Tests\TestCase;

class RomajiTest extends TestCase
{
    public static function tearDownAfterClass(): void
    {
        $config = Config::getInstance();

        $config->resetConfig();
    }

    public function testItMakesHepburnModifiedRomaji(): void
    {
        self::$limelight->setConfig('hepburn_modified', 'Romaji', 'style');

        $results = self::$limelight->parse('今日は翔太と一緒に群馬県に行って、私のお姉さんと抹茶を飲みました。')->string('romaji', ' ');

        $this->assertEquals('kyō wa Shōta to issho ni Gunmaken ni itte, watashi no o nēsan to matcha o nomimashita.', $results);
    }

    public function testItMakesHepburnTraditionalRomaji(): void
    {
        self::$limelight->setConfig('hepburn_traditional', 'Romaji', 'style');

        $results = self::$limelight->parse('今日は翔太と一緒に群馬県に行って、私のお姉さんと抹茶を飲みました。')->string('romaji', ' ');

        $this->assertEquals('kyō wa Shōta to issho ni Gummaken ni itte, watashi no o neesan to matcha wo nomimashita.', $results);
    }

    public function testItMakesKunreiShikiRomaji(): void
    {
        self::$limelight->setConfig('kunrei_shiki', 'Romaji', 'style');

        $results = self::$limelight->parse('今日は翔太と一緒に群馬県に行って、私のお姉さんと抹茶を飲みました。')->string('romaji', ' ');

        $this->assertEquals('kyô wa Syôta to issyo ni Gunmaken ni itte, watasi no o nêsan to mattya o nomimasita.', $results);
    }

    public function testItMakesNihonShikiRomaji(): void
    {
        self::$limelight->setConfig('nihon_shiki', 'Romaji', 'style');

        $results = self::$limelight->parse('今日は翔太と一緒に群馬県に行って、私のお姉さんと抹茶を飲みました。')->string('romaji', ' ');

        $this->assertEquals('kyô ha Syôta to issyo ni Gunmaken ni itte, watasi no o nêsan to mattya wo nomimasita.', $results);
    }
}
