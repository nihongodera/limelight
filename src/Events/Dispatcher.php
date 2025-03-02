<?php

declare(strict_types=1);

namespace Limelight\Events;

use Limelight\Exceptions\EventErrorException;

class Dispatcher
{
    /**
     * Registered listeners.
     */
    protected array $registeredListeners = [];

    /**
     * When false, events are fired.
     */
    protected bool $suppressEvents = false;

    public function __construct(array $configListeners)
    {
        $this->addAllListeners($configListeners);
    }

    /**
     * Add array of listeners from config.
     */
    public function addAllListeners(array $configListeners): void
    {
        array_walk($configListeners, [$this, 'addListeners']);
    }

    /**
     * Add a single or multiple listeners.
     *
     * @param LimelightListener|array $listeners
     */
    public function addListeners($listeners, string $eventName): void
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
    public function clearListeners(): void
    {
        $this->registeredListeners = [];
    }

    /**
     * Get all registered listeners.
     */
    public function getListeners(): array
    {
        return $this->registeredListeners;
    }

    /**
     * Call handle method on all listeners for event.
     */
    public function fire(string $eventName, $payload = null)
    {
        if (isset($this->registeredListeners[$eventName]) && $this->suppressEvents === false) {
            $listeners = $this->registeredListeners[$eventName];

            return array_map(static function (LimelightListener $listener) use ($payload) {
                return $listener->handle($payload);
            }, $listeners);
        }

        return false;
    }

    /**
     * Turn eventing on/off.
     */
    public function toggleEvents(bool $suppressEvents): bool
    {
        if ($suppressEvents === true && $this->suppressEvents === false) {
            $this->suppressEvents = true;
        } elseif ($suppressEvents === true && $this->suppressEvents === true) {
            $this->suppressEvents = false;
        }

        return $this->suppressEvents;
    }
}
