<?php

namespace Tonis\Event\TestAsset;

use Tonis\Event\EventManager;
use Tonis\Event\SubscriberInterface;

class BasicSubscriber implements SubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public function subscribe(EventManager $events)
    {
        $events->on('foo', [$this, 'onFoo']);
    }

    /**
     * @return string
     */
    public function onFoo()
    {
        return 'fired';
    }
}
