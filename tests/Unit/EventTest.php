<?php

declare(strict_types=1);

namespace Limelight\tests\Unit;

use Limelight\Config\Config;
use Limelight\Tests\TestCase;
use Limelight\Events\Dispatcher;
use Limelight\Tests\Stubs\TestListener;
use Limelight\Exceptions\EventErrorException;

class EventTest extends TestCase
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
    public function dispatcher_can_be_instantiated(): void
    {
        $dispatcher = $this->buildDispatcher();

        $this->assertInstanceOf(Dispatcher::class, $dispatcher);
    }

    /**
     * @test
     */
    public function dispatcher_can_add_a_single_listener(): void
    {
        $dispatcher = $this->buildDispatcher();

        $listener = new TestListener();

        $dispatcher->addListeners($listener, 'WordWasCreated');

        $registeredListeners = $dispatcher->getListeners();

        $this->assertInstanceOf(TestListener::class, $registeredListeners['WordWasCreated'][0]);
    }

    /**
     * @test
     */
    public function dispatcher_can_add_an_array_of_listeners(): void
    {
        $dispatcher = $this->buildDispatcher();

        $listener = [new TestListener()];

        $dispatcher->addListeners($listener, 'WordWasCreated');

        $registeredListeners = $dispatcher->getListeners();

        $this->assertInstanceOf(TestListener::class, $registeredListeners['WordWasCreated'][0]);
    }

    /**
     * @test
     */
    public function dispatcher_can_add_all_listeners(): void
    {
        $dispatcher = $this->buildDispatcher();

        $listeners = [
            'WordWasCreated' => [
                TestListener::class,
            ],
            'ParseWasSuccessful' => [
                TestListener::class,
            ],
        ];

        $dispatcher->addAllListeners($listeners);

        $registeredListeners = $dispatcher->getListeners();

        $this->assertInstanceOf(TestListener::class, $registeredListeners['WordWasCreated'][0]);
    }

    /**
     * @test
     */
    public function dispatcher_can_fire_listener(): void
    {
        $dispatcher = $this->buildDispatcher();

        $listener = new TestListener();

        $dispatcher->addListeners($listener, 'WordWasCreated');

        $result = $dispatcher->fire('WordWasCreated');

        $this->assertEquals('It works!', $result[0]);
    }

    /**
     * @test
     */
    public function dispatcher_can_fire_multiple_listeners(): void
    {
        $dispatcher = $this->buildDispatcher();

        $listener = [new TestListener(), new TestListener()];

        $dispatcher->addListeners($listener, 'WordWasCreated');

        $result = $dispatcher->fire('WordWasCreated');

        $this->assertCount(2, $result);
    }

    /**
     * @test
     */
    public function dispatcher_sends_payload(): void
    {
        $dispatcher = $this->buildDispatcher();

        $listener = new TestListener();

        $dispatcher->addListeners($listener, 'WordWasCreated');

        $result = $dispatcher->fire('WordWasCreated', 'Hello!');

        $this->assertEquals('Payload says Hello!', $result[0]);
    }

    /**
     * @test
     */
    public function dispatcher_can_be_supressed(): void
    {
        $dispatcher = $this->buildDispatcher();

        $dispatcher->toggleEvents(true);

        $listener = new TestListener();

        $dispatcher->addListeners($listener, 'WordWasCreated');

        $result = $dispatcher->fire('WordWasCreated');

        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function dispatcher_toggles_event_suppression(): void
    {
        $dispatcher = $this->buildDispatcher();

        $suppressEvents = $dispatcher->toggleEvents(true);

        $this->assertTrue($suppressEvents);

        $suppressEvents = $dispatcher->toggleEvents(true);

        $this->assertFalse($suppressEvents);
    }

    /**
     * @test
     */
    public function dispatcher_clears_all_registered_events(): void
    {
        $dispatcher = $this->buildDispatcher();

        $listener = new TestListener();

        $dispatcher->addListeners($listener, 'WordWasCreated');

        $registeredListeners = $dispatcher->getListeners();

        $this->assertInstanceOf(TestListener::class, $registeredListeners['WordWasCreated'][0]);

        $dispatcher->clearListeners();

        $registeredListeners = $dispatcher->getListeners();

        $this->assertEquals([], $registeredListeners);
    }

    /**
     * @test
     */
    public function dispatcher_throws_error_if_listener_class_doesnt_exist(): void
    {
        $this->expectExceptionMessage("Class I\Dont\Exist does not exist.");
        $this->expectException(EventErrorException::class);
        $dispatcher = $this->buildDispatcher();

        $listener = 'I\Dont\Exist';

        $dispatcher->addListeners($listener, 'WordWasCreated');
    }

    /**
     * Build instance of Dispatcher.
     */
    protected function buildDispatcher(): Dispatcher
    {
        $config = Config::getInstance();

        return new Dispatcher($config->get('listeners'));
    }
}
