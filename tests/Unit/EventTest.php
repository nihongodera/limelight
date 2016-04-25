<?php

namespace Limelight\tests\Unit;

use Limelight\Config\Config;
use Limelight\Tests\TestCase;
use Limelight\Events\Dispatcher;
use Limelight\Tests\Stubs\TestListener;

class EventTest extends TestCase
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
    public function dispatcher_can_be_instantiated()
    {
        $dispatcher = $this->buildDispatcher();

        $this->assertInstanceOf('Limelight\Events\Dispatcher', $dispatcher);
    }

    /**
     * @test
     */
    public function dispatcher_can_add_a_single_listener()
    {
        $dispatcher = $this->buildDispatcher();

        $listener = new TestListener();

        $dispatcher->addListeners($listener, 'WordWasCreated');

        $registeredListeners = $dispatcher->getListeners();

        $this->assertInstanceOf('Limelight\Tests\Stubs\TestListener', $registeredListeners['WordWasCreated'][0]);
    }

    /**
     * @test
     */
    public function dispatcher_can_add_an_array_of_listeners()
    {
        $dispatcher = $this->buildDispatcher();

        $listener = [new TestListener()];

        $dispatcher->addListeners($listener, 'WordWasCreated');

        $registeredListeners = $dispatcher->getListeners();

        $this->assertInstanceOf('Limelight\Tests\Stubs\TestListener', $registeredListeners['WordWasCreated'][0]);
    }

    /**
     * @test
     */
    public function dispatcher_can_add_all_listeners()
    {
        $dispatcher = $this->buildDispatcher();

        $listeners = [
            'WordWasCreated' => [
                'Limelight\Tests\Stubs\TestListener'
            ],
            'ParseWasSuccessful' => [
                'Limelight\Tests\Stubs\TestListener'
            ]
        ];

        $dispatcher->addAllListeners($listeners);

        $registeredListeners = $dispatcher->getListeners();

        $this->assertInstanceOf('Limelight\Tests\Stubs\TestListener', $registeredListeners['WordWasCreated'][0]);
    }

    /**
     * @test
     */
    public function dispatcher_can_fire_listener()
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
    public function dispatcher_can_fire_multiple_listeners()
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
    public function dispatcher_sends_payload()
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
    public function dispatcher_can_be_supressed()
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
    public function dispatcher_toggles_event_supression()
    {
        $dispatcher = $this->buildDispatcher();

        $supressEvents = $dispatcher->toggleEvents(true);

        $this->assertTrue($supressEvents);

        $supressEvents = $dispatcher->toggleEvents(true);

        $this->assertFalse($supressEvents);
    }

    /**
     * @test
     */
    public function dispatcher_clears_all_registered_events()
    {
        $dispatcher = $this->buildDispatcher();

        $listener = new TestListener();

        $dispatcher->addListeners($listener, 'WordWasCreated');

        $registeredListeners = $dispatcher->getListeners();

        $this->assertInstanceOf('Limelight\Tests\Stubs\TestListener', $registeredListeners['WordWasCreated'][0]);

        $dispatcher->clearListeners();

        $registeredListeners = $dispatcher->getListeners();

        $this->assertEquals([], $registeredListeners);
    }

    /**
     * @test
     *
     * @expectedException Limelight\Exceptions\EventErrorException
     * @expectedExceptionMessage Class I\Dont\Exist does not exist.
     */
    public function dispatcher_throws_error_if_listener_class_doesnt_exist()
    {
        $dispatcher = $this->buildDispatcher();

        $listener = 'I\Dont\Exist';

        $dispatcher->addListeners($listener, 'WordWasCreated');
    }

    /**
     * Build instance of Dispatcher.
     *
     * @return Limelight\Events\Dispatcher
     */
    protected function buildDispatcher()
    {
        $config = Config::getInstance();

        return new Dispatcher($config->get('listeners'));
    }
}
