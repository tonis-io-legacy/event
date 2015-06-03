<?php

namespace Tonis\Event;

/**
 * @coversDefaultClass \Tonis\Event\Event
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
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

    /**
     * @covers ::getName
     */
    public function testGetName()
    {
        $event = new Event('foo');
        $this->assertSame('foo', $event->getName());
    }
}
