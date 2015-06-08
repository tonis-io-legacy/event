<?php

namespace Tonis\Event\TestAsset;

use Tonis\Event\EventsAwareTrait;
use Tonis\Event\EventManager;

class EventsAware
{
    use EventsAwareTrait;

    protected function attachDefaultListeners(EventManager $events)
    {
        $events->subscribe(new BasicSubscriber());
    }
}
