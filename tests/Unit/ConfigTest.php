<?php

namespace Limelight\tests\Unit;

use Limelight\Config\Config;
use Limelight\Tests\TestCase;

class ConfigTest extends TestCase
{
    /**
     * Reset config file.
     */
    public static function tearDownAfterClass()
    {
        $config = Config::getInstance();

        $config->resetConfig();
    }

    /**
     * @test
     */
    public function it_gets_config_values()
    {
        $config = Config::getInstance();

        $plugins = $config->get('plugins');

        $this->assertTrue(is_array($plugins));
    }

    /**
     * @test
     * @expectedException Limelight\Exceptions\InvalidInputException
     * @expectedExceptionMessage Index pluins does not exist in config.php.
     */
    public function it_throws_exception_for_getting_invalid_config_value()
    {
        $config = Config::getInstance();

        $plugins = $config->get('pluins');
    }

    /**
     * @test
     */
    public function it_gets_an_instance_of_itself()
    {
        $config = Config::getInstance();

        $this->assertInstanceOf('Limelight\Config\Config', $config);
    }

    /**
     * @test
     */
    public function it_gets_plugins_from_file()
    {
        $config = Config::getInstance();

        $plugins = $config->getPlugins();

        $this->assertTrue(is_array($plugins));
    }

    /**
     * @test
     */
    public function it_makes_mecab()
    {
        $config = Config::getInstance();

        $mecab = $config->make('Limelight\Mecab\Mecab');

        $this->assertInstanceOf('Limelight\Mecab\Mecab', $mecab);
    }

    /**
     * @test
     * @expectedException Limelight\Exceptions\InternalErrorException
     * @expectedExceptionMessage Class kljsdf defined in config.php does not exist.
     */
    public function it_throws_exception_when_it_cant_make_mecab()
    {
        $config = Config::getInstance();

        $config->set('kljsdf', 'bindings', 'Limelight\Mecab\Mecab');

        $config->make('Limelight\Mecab\Mecab');
    }

    /**
     * @test
     */
    public function it_resets_config_file()
    {
        $config = Config::getInstance();

        $romaji = $config->get('Romaji');

        $this->assertEquals('hepburn_modified', $romaji['style']);

        $config->set('hepburn_traditional', 'Romaji', 'style');

        $romaji = $config->get('Romaji');

        $this->assertEquals('hepburn_traditional', $romaji['style']);

        $config->resetConfig();

        $romaji = $config->get('Romaji');

        $this->assertEquals('hepburn_modified', $romaji['style']);
    }

    /**
     * @test
     */
    public function it_sets_values_on_config_file()
    {
        $config = Config::getInstance();

        $romaji = $config->get('Romaji');

        $this->assertEquals('hepburn_modified', $romaji['style']);

        $config->set('hepburn_traditional', 'Romaji', 'style');

        $romaji = $config->get('Romaji');

        $this->assertEquals('hepburn_traditional', $romaji['style']);

        $config->resetConfig();
    }

    /**
     * @test
     * @expectedException Limelight\Exceptions\InvalidInputException
     * @expectedExceptionMessage Key not found in config file.
     */
    public function it_throws_exception_for_setting_invalid_config_value()
    {
        $config = Config::getInstance();

        $config->set('test', 'sldkfj', 'lkfsd');

        $config->resetConfig();
    }
}
