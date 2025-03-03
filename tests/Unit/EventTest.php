<?php

declare(strict_types=1);

namespace Limelight\tests\Unit;

use Limelight\Config\Config;
use Limelight\Events\Dispatcher;
use Limelight\Exceptions\EventErrorException;
use Limelight\Tests\Stubs\TestListener;
use Limelight\Tests\TestCase;

class EventTest extends TestCase
{
    public static function tearDownAfterClass(): void
    {
        $config = Config::getInstance();

        $config->resetConfig();
    }

    public function testDispatcherCanBeInstantiated(): void
    {
        $dispatcher = $this->buildDispatcher();

        $this->assertInstanceOf(Dispatcher::class, $dispatcher);
    }

    public function testDispatcherCanAddASingleListener(): void
    {
        $dispatcher = $this->buildDispatcher();

        $listener = new TestListener();

        $dispatcher->addListeners($listener, 'WordWasCreated');

        $registeredListeners = $dispatcher->getListeners();

        $this->assertInstanceOf(TestListener::class, $registeredListeners['WordWasCreated'][0]);
    }

    public function testDispatcherCanAddAnArrayOfListeners(): void
    {
        $dispatcher = $this->buildDispatcher();

        $listener = [new TestListener()];

        $dispatcher->addListeners($listener, 'WordWasCreated');

        $registeredListeners = $dispatcher->getListeners();

        $this->assertInstanceOf(TestListener::class, $registeredListeners['WordWasCreated'][0]);
    }

    public function testDispatcherCanAddAllListeners(): void
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

    public function testDispatcherCanFireListener(): void
    {
        $dispatcher = $this->buildDispatcher();

        $listener = new TestListener();

        $dispatcher->addListeners($listener, 'WordWasCreated');

        $result = $dispatcher->fire('WordWasCreated');

        $this->assertEquals('It works!', $result[0]);
    }

    public function testDispatcherCanFireMultipleListeners(): void
    {
        $dispatcher = $this->buildDispatcher();

        $listener = [new TestListener(), new TestListener()];

        $dispatcher->addListeners($listener, 'WordWasCreated');

        $result = $dispatcher->fire('WordWasCreated');

        $this->assertCount(2, $result);
    }

    public function testDispatcherSendsPayload(): void
    {
        $dispatcher = $this->buildDispatcher();

        $listener = new TestListener();

        $dispatcher->addListeners($listener, 'WordWasCreated');

        $result = $dispatcher->fire('WordWasCreated', 'Hello!');

        $this->assertEquals('Payload says Hello!', $result[0]);
    }

    public function testDispatcherCanBeSuppressed(): void
    {
        $dispatcher = $this->buildDispatcher();

        $dispatcher->toggleEvents(true);

        $listener = new TestListener();

        $dispatcher->addListeners($listener, 'WordWasCreated');

        $result = $dispatcher->fire('WordWasCreated');

        $this->assertFalse($result);
    }

    public function testDispatcherTogglesEventSuppression(): void
    {
        $dispatcher = $this->buildDispatcher();

        $suppressEvents = $dispatcher->toggleEvents(true);

        $this->assertTrue($suppressEvents);

        $suppressEvents = $dispatcher->toggleEvents(true);

        $this->assertFalse($suppressEvents);
    }

    public function testDispatcherClearsAllRegisteredEvents(): void
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

    public function testDispatcherThrowsErrorIfListenerClassDoesntExist(): void
    {
        $this->expectExceptionMessage("Class I\Dont\Exist does not exist.");
        $this->expectException(EventErrorException::class);
        $dispatcher = $this->buildDispatcher();

        $listener = 'I\Dont\Exist';

        $dispatcher->addListeners($listener, 'WordWasCreated');
    }

    protected function buildDispatcher(): Dispatcher
    {
        $config = Config::getInstance();

        return new Dispatcher($config->get('listeners'));
    }
}
