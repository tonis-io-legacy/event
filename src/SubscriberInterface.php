<?php

namespace Tonis\Event;

interface SubscriberInterface
{
    /**
     * @param EventManager $events
     * @return void
     */
    public function subscribe(EventManager $events);
}
