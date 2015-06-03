<?php

namespace Tonis\Event\TestAsset;

use Tonis\Event\EventsAwareTrait;
use Tonis\Event\Manager;

class EventsAware
{
    use EventsAwareTrait;

    protected function attachDefaultListeners(Manager $events)
    {
        $events->subscribe(new BasicSubscriber());
    }
}
