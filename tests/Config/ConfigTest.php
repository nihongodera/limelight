<?php

namespace Limelight\Tests\Config;

use Limelight\Limelight;
use Limelight\Config\Config;
use Limelight\Tests\TestCase;

class ConfigTest extends TestCase
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
     * Reset config file.
     */
    public static function tearDownAfterClass()
    {
        $config = Config::getInstance();

        $config->resetConfig();
    }

    /**
     * It gets config values.
     *
     * @test
     */
    public function it_gets_config_values()
    {
        $config = Config::getInstance();

        $plugins = $config->get('plugins');

        $this->assertTrue(is_array($plugins));
    }

    /**
     * Invalid config value throws error.
     *
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
     * It gets an instance of itself.
     *
     * @test
     */
    public function it_gets_an_instance_of_itself()
    {
        $config = Config::getInstance();

        $this->assertInstanceOf('Limelight\Config\Config', $config);
    }

    /**
     * It gets plugins from config file.
     *
     * @test
     */
    public function it_gets_plugins_from_file()
    {
        $config = Config::getInstance();

        $plugins = $config->getPlugins();

        $this->assertTrue(is_array($plugins));
    }

    /**
     * It makes mecab.
     *
     * @test
     */
    public function it_makes_mecab()
    {
        $config = Config::getInstance();

        $mecab = $config->make('Limelight\Mecab\Mecab');

        $this->assertInstanceOf('Limelight\Mecab\Mecab', $mecab);
    }

    /**
     * It throws exception when it cant make mecab.
     *
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
     * It resets the config file.
     *
     * @test
     */
    public function it_resets_config_file()
    {
        $config = Config::getInstance();

        $romanji = $config->get('Romanji');

        $this->assertEquals('hepburn_modified', $romanji['style']);

        $config->set('hepburn_traditional', 'Romanji', 'style');

        $romanji = $config->get('Romanji');

        $this->assertEquals('hepburn_traditional', $romanji['style']);

        $config->resetConfig();

        $romanji = $config->get('Romanji');

        $this->assertEquals('hepburn_modified', $romanji['style']);
    }

    /**
     * It sets values on config file.
     *
     * @test
     */
    public function it_sets_values_on_config_file()
    {
        $config = Config::getInstance();

        $romanji = $config->get('Romanji');

        $this->assertEquals('hepburn_modified', $romanji['style']);

        $config->set('hepburn_traditional', 'Romanji', 'style');

        $romanji = $config->get('Romanji');

        $this->assertEquals('hepburn_traditional', $romanji['style']);
    }

    /**
     * Invalid set key throws error.
     *
     * @test
     * @expectedException Limelight\Exceptions\InvalidInputException
     * @expectedExceptionMessage Key not found in config file.
     */
    public function it_throws_exception_for_setting_invalid_config_value()
    {
        $config = Config::getInstance();

        $config->set('test', 'sldkfj', 'lkfsd');
    }
}
