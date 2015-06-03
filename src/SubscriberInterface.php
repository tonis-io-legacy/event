<?php

namespace Tonis\Event;

interface SubscriberInterface
{
    /**
     * @param Manager $events
     * @return void
     */
    public function subscribe(Manager $events);
}
