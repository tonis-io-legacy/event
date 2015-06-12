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
    }

    /**
     * @return EventManager
     */
    public function getEventManager()
    {
        if (!$this->events instanceof EventManager) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }
}
