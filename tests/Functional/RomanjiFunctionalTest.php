<?php

namespace Limelight\Tests\Functional;

use Limelight\Limelight;
use Limelight\Config\Config;
use Limelight\Tests\TestCase;

class RomanjiFunctionalTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    protected static $limelight;

    /**
     * Set static limelight and test libs on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();
    }

    /**
     * Reset config file.
     */
    public static function tearDownAfterClass()
    {
        $config = Config::getInstance();

        $config->resetConfig();
    }

    /**
     * It makes Hepburn Modified Romanji.
     * 
     * @test
     */
    public function it_makes_hepburn_modified_romanji()
    {
        self::$limelight->setConfig('hepburn_modified', 'Romanji', 'style');

        $results = self::$limelight->parse('今日は翔太と一緒に群馬県に行って、私のお姉さんと抹茶を飲みました。');

        $this->assertEquals('Kyō wa Shōta to issho ni Gunmaken ni itte, watashi no o nēsan to matcha o nomimashita.', $results->plugin('Romanji'));
    }

    /**
     * It makes Hepburn Traditional Romanji.
     * 
     * @test
     */
    public function it_makes_hepburn_traditional_romanji()
    {
        self::$limelight->setConfig('hepburn_traditional', 'Romanji', 'style');

        $results = self::$limelight->parse('今日は翔太と一緒に群馬県に行って、私のお姉さんと抹茶を飲みました。');

        $this->assertEquals('Kyō wa Shōta to issho ni Gummaken ni itte, watashi no o neesan to matcha wo nomimashita.', $results->plugin('Romanji'));
    }

    /**
     * It makes Kunrei Shiki Romanji.
     * 
     * @test
     */
    public function it_makes_kunrei_shiki_romanji()
    {
        self::$limelight->setConfig('kunrei_shiki', 'Romanji', 'style');

        $results = self::$limelight->parse('今日は翔太と一緒に群馬県に行って、私のお姉さんと抹茶を飲みました。');

        $this->assertEquals('Kyô wa Syôta to issyo ni Gunmaken ni itte, watasi no o nêsan to mattya o nomimasita.', $results->plugin('Romanji'));
    }

    /**
     * It makes Nihon Shiki Romanji.
     * 
     * @test
     */
    public function it_makes_nihon_shiki_romanji()
    {
        self::$limelight->setConfig('nihon_shiki', 'Romanji', 'style');

        $results = self::$limelight->parse('今日は翔太と一緒に群馬県に行って、私のお姉さんと抹茶を飲みました。');

        $this->assertEquals('Kyô ha Syôta to issyo ni Gunmaken ni itte, watasi no o nêsan to mattya wo nomimasita.', $results->plugin('Romanji'));
    }
}