<?php

namespace Tonis\Event;

interface EventManagerInterface
{
    /**
     * Attaches events using a subscriber.
     *
     * @param SubscriberInterface $subscriber
     * @return void
     */
    public function subscribe(SubscriberInterface $subscriber);

    /**
     * Attaches an event to the queue using the $name as the identifier.
     *
     * @param string $name
     * @param callable $callable
     * @param int $priority
     * @throws Exception\InvalidCallableException
     * @return void
     */
    public function on($name, $callable, $priority = 0);

    /**
     * @param null $name
     * @return array
     */
    public function getListeners($name);

    /**
     * Fires an event.
     *
     * @param string $name
     * @param null|EventInterface $event
     * @return \SplQueue
     */
    public function fire($name, EventInterface $event = null);

    /**
     * Clears all events.
     * @return void
     */
    public function clear();
}
