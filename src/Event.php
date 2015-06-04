<?php

namespace Tonis\Event;

class Event implements EventInterface
{
    /** @var bool */
    private $stopped = false;

    /**
     * {@inheritDoc}
     */
    public function stop()
    {
        $this->stopped = true;
    }

    /**
     * {@inheritDoc}
     */
    public function isStopped()
    {
        return $this->stopped;
    }
}
