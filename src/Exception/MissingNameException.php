<?php

namespace Tonis\Event\Exception;

class MissingNameException extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Event given but no name specified');
    }
}
