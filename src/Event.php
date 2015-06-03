<?php

namespace Tonis\Event;

class Event implements EventInterface
{
    /** @var string */
    protected $name;
    /** @var bool */
    private $stopped = false;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

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

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }
}
