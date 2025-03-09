<?php

declare(strict_types=1);

namespace Limelight\tests\Unit;

use Limelight\Config\Config;
use Limelight\Exceptions\InternalErrorException;
use Limelight\Exceptions\InvalidInputException;
use Limelight\Mecab\Mecab;
use Limelight\Tests\TestCase;

class ConfigTest extends TestCase
{
    public static function tearDownAfterClass(): void
    {
        $config = Config::getInstance();

        $config->resetConfig();
    }

    public function testItGetsConfigValues(): void
    {
        $config = Config::getInstance();

        $plugins = $config->get('plugins');

        $this->assertIsArray($plugins);
    }

    public function testItThrowsExceptionForGettingInvalidConfigValue(): void
    {
        $this->expectExceptionMessage('Index pluins does not exist in config.php.');
        $this->expectException(InvalidInputException::class);

        $config = Config::getInstance();

        $config->get('pluins');
    }

    public function testItGetsAnInstanceOfItself(): void
    {
        $config = Config::getInstance();

        $this->assertInstanceOf(Config::class, $config);
    }

    public function testItGetsPluginsFromFile(): void
    {
        $config = Config::getInstance();

        $plugins = $config->getPlugins();

        $this->assertIsArray($plugins);
    }

    public function testItMakesMecab(): void
    {
        $config = Config::getInstance();

        $mecab = $config->make(Mecab::class);

        $this->assertInstanceOf(Mecab::class, $mecab);
    }

    public function testItThrowsExceptionWhenItCantMakeMecab(): void
    {
        $this->expectExceptionMessage('Class kljsdf defined in config.php does not exist.');
        $this->expectException(InternalErrorException::class);

        $config = Config::getInstance();

        $config->set('kljsdf', 'bindings', Mecab::class);

        $config->make(Mecab::class);
    }

    public function testItResetsConfigFile(): void
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

    public function testItSetsValuesOnConfigFile(): void
    {
        $config = Config::getInstance();

        $romaji = $config->get('Romaji');

        $this->assertEquals('hepburn_modified', $romaji['style']);

        $config->set('hepburn_traditional', 'Romaji', 'style');

        $romaji = $config->get('Romaji');

        $this->assertEquals('hepburn_traditional', $romaji['style']);

        $config->resetConfig();
    }

    public function testItThrowsExceptionForSettingInvalidConfigValue(): void
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
