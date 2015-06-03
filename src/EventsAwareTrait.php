<?php

namespace Tonis\Event;

trait EventsAwareTrait
{
    /** @var Manager */
    protected $events;

    /**
     * @param Manager $events
     */
    public function setEventManager(Manager $events)
    {
        $this->events = $events;
        $this->attachDefaultListeners($events);
    }

    /**
     * @return Manager
     */
    public function events()
    {
        if (!$this->events instanceof Manager) {
            $this->setEventManager(new Manager());
        }
        return $this->events;
    }

    /**
     * @codeCoverageIgnore
     * @param Manager $events
     */
    protected function attachDefaultListeners(Manager $events)
    {
    }
}
