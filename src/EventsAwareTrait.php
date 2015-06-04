<?php

namespace Tonis\Event;

trait EventsAwareTrait
{
    /** @var EventManager */
    protected $events;

    /**
     * @param EventManager $events
     */
    public function setEventManager(EventManager $events)
    {
        $this->events = $events;
        $this->attachDefaultListeners($events);
    }

    /**
     * @return EventManager
     */
    public function events()
    {
        if (!$this->events instanceof EventManager) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }

    /**
     * @codeCoverageIgnore
     * @param EventManager $events
     */
    protected function attachDefaultListeners(EventManager $events)
    {
    }
}
