<?php
namespace Tonis\Event;

interface EventInterface
{
    /**
     * Stops the event from propogating.
     *
     * @return void
     */
    public function stop();

    /**
     * @return bool
     */
    public function isStopped();

    /**
     * @return string
     */
    public function getName();
}
