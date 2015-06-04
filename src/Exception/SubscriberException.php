<?php

namespace Tonis\Event\Exception;

use Tonis\Event\Event;

class SubscriberException extends \RuntimeException
{
    /**
     * @param string $name
     * @param Event $event
     * @param \Exception $exception
     */
    public function __construct($name, Event $event, \Exception $exception)
    {
        $msg = sprintf(
            'Error: exception while firing "%s" caught from %s::%d',
            $name,
            $exception->getFile(),
            $exception->getLine()
        );

        parent::__construct($msg, 0, $exception);
    }
}
