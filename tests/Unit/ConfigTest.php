<?php

declare(strict_types=1);

namespace Limelight\tests\Unit;

use Limelight\Mecab\Mecab;
use Limelight\Config\Config;
use Limelight\Tests\TestCase;
use Limelight\Exceptions\InvalidInputException;
use Limelight\Exceptions\InternalErrorException;

class ConfigTest extends TestCase
{
    /**
     * Reset config file.
     */
    public static function tearDownAfterClass(): void
    {
        $config = Config::getInstance();

        $config->resetConfig();
    }

    /**
     * @test
     */
    public function it_gets_config_values(): void
    {
        $config = Config::getInstance();

        $plugins = $config->get('plugins');

        $this->assertIsArray($plugins);
    }

    /**
     * @test
     */
    public function it_throws_exception_for_getting_invalid_config_value(): void
    {
        $this->expectExceptionMessage('Index pluins does not exist in config.php.');
        $this->expectException(InvalidInputException::class);

        $config = Config::getInstance();

        $config->get('pluins');
    }

    /**
     * @test
     */
    public function it_gets_an_instance_of_itself(): void
    {
        $config = Config::getInstance();

        $this->assertInstanceOf(Config::class, $config);
    }

    /**
     * @test
     */
    public function it_gets_plugins_from_file(): void
    {
        $config = Config::getInstance();

        $plugins = $config->getPlugins();

        $this->assertIsArray($plugins);
    }

    /**
     * @test
     */
    public function it_makes_mecab(): void
    {
        $config = Config::getInstance();

        $mecab = $config->make(Mecab::class);

        $this->assertInstanceOf(Mecab::class, $mecab);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_it_cant_make_mecab(): void
    {
        $this->expectExceptionMessage('Class kljsdf defined in config.php does not exist.');
        $this->expectException(InternalErrorException::class);

        $config = Config::getInstance();

        $config->set('kljsdf', 'bindings', Mecab::class);

        $config->make(Mecab::class);
    }

    /**
     * @test
     */
    public function it_resets_config_file(): void
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
    public function it_sets_values_on_config_file(): void
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
     */
    public function it_throws_exception_for_setting_invalid_config_value(): void
    {
        $this->expectExceptionMessage('Key not found in config file.');
        $this->expectException(InvalidInputException::class);

        $config = Config::getInstance();

        try {
            $config->set('test', 'sldkfj', 'lkfsd');
        } finally {
            $config->resetConfig();
        }
    }
}
