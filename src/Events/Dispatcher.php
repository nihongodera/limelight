<?php

namespace Limelight\Events;

use Limelight\Exceptions\EventErrorException;

class Dispatcher
{
    /**
     * Registered listeners.
     *
     * @var array
     */
    protected $registeredListeners = [];

    /**
     * When false, events are fired.
     *
     * @var bool
     */
    protected $supressEvents = false;

    /**
     * Construct.
     *
     * @param array $configListeners
     */
    public function __construct(array $configListeners)
    {
        $this->addAllListeners($configListeners);
    }

    /**
     * Add array of listeners from config.
     *
     * @param array $configListeners
     */
    public function addAllListeners(array $configListeners)
    {
        array_walk($configListeners, [$this, 'addListeners']);
    }

    /**
     * Add a single listener.
     *
     * @param LimelightListener|array $listeners
     * @param string                  $eventName
     */
    public function addListeners($listeners, $eventName)
    {
        $listeners = (is_array($listeners) ? $listeners : [$listeners]);

        array_walk($listeners, function ($listener) use ($eventName) {
            if (is_string($listener) && !class_exists($listener)) {
                throw new EventErrorException("Class {$listener} does not exist.");
            }

            $this->registeredListeners[$eventName][] = new $listener();
        });
    }

    /**
     * Clear all registered listeners.
     */
    public function clearListeners()
    {
        $this->registeredListeners = [];
    }

    /**
     * Get all registered listeners.
     *
     * @return array
     */
    public function getListeners()
    {
        return $this->registeredListeners;
    }

    /**
     * Call handle method on all listeners for event.
     *
     * @param string $eventName
     * @param mixed  $payload
     *
     * @return mixed
     */
    public function fire($eventName, $payload = null)
    {
        if (isset($this->registeredListeners[$eventName]) && $this->supressEvents === false) {
            $listeners = $this->registeredListeners[$eventName];

            return array_map(function (LimelightListener $listener) use ($payload) {
                return $listener->handle($payload);
            }, $listeners);
        }

        return false;
    }

    /**
     * Turn eventing on/off.
     *
     * @param bool $supressEvents
     *
     * @return bool
     */
    public function toggleEvents($supressEvents)
    {
        if ($supressEvents === true && $this->supressEvents === false) {
            $this->supressEvents = true;
        } elseif ($supressEvents === true && $this->supressEvents === true) {
            $this->supressEvents = false;
        }

        return $this->supressEvents;
    }
}
