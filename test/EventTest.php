<?php

namespace Tonis\Event;

/**
 * @coversDefaultClass \Tonis\Event\Event
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::stop
     * @covers ::isStopped
     */
    public function testStopped()
    {
        $event = new Event('foo');
        $this->assertFalse($event->isStopped());
        $event->stop();
        $this->assertTrue($event->isStopped());
    }
}
