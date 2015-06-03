<?php

namespace Tonis\Event\Exception;

use Tonis\Event\Event;

class SubscriberException extends \RuntimeException
{
    /**
     * @param Event $event
     * @param \Exception $exception
     */
    public function __construct(Event $event, \Exception $exception)
    {
        $msg = sprintf(
            'Error: exception while firing "%s" caught from %s::%d',
            $event->getName(),
            $exception->getFile(),
            $exception->getLine()
        );

        parent::__construct($msg, 0, $exception);
    }
}
