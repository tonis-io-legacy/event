<?php

namespace Tonis\Event;

final class EventManager implements EventManagerInterface
{
    /**
     * @var array
     */
    protected $listeners = [];

    /**
     * This is used to give some regularity (FIFO) to SplPriorityQueue when queueing
     * with the same priority.
     *
     * @var int
     */
    protected $queueOrder = PHP_INT_MAX;

    /**
     * {@inheritDoc}
     */
    public function getListeners($name = null)
    {
        if (null !== $name) {
            return empty($this->listeners[$name]) ? [] : $this->listeners[$name];
        }
        return $this->listeners;
    }

    /**
     * {@inheritDoc}
     */
    public function clear($name = null)
    {
        if (null === $name) {
            $this->listeners = [];
            return;
        }

        if (isset($this->listeners[$name])) {
            unset($this->listeners[$name]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function subscribe(SubscriberInterface $subscriber)
    {
        $subscriber->subscribe($this);
    }

    /**
     * {@inheritDoc}
     */
    public function on($name, $callable, $priority = 0)
    {
        if (!is_callable($callable)) {
            throw new Exception\InvalidCallableException($callable);
        }

        if (!is_int($priority)) {
            throw new Exception\InvalidPriorityException($priority);
        }

        $this->getQueue($name)->insert($callable, [$priority, $this->queueOrder--]);
    }

    /**
     * {@inheritDoc}
     */
    public function fire($name, EventInterface $event = null)
    {
        $event = $event?: new Event();
        $response = new \SplQueue();
        $queue = clone $this->getQueue($name);

        foreach ($queue as $callable) {
            $result = is_callable($callable) ? $callable($event) : null;
            $response->enqueue($result);

            if ($event->isStopped()) {
                break;
            }
        }

        return $response;
    }

    /**
     * @param string $name
     * @return \SplPriorityQueue
     */
    private function getQueue($name)
    {
        if (!array_key_exists($name, $this->listeners)) {
            $this->listeners[$name] = new \SplPriorityQueue();
        }
        return $this->listeners[$name];
    }
}
