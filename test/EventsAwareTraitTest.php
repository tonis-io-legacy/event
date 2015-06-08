<?php

namespace Tonis\Event;

use Tonis\Event\TestAsset\EventsAware;

/**
 * @coversDefaultClass \Tonis\Event\EventsAwareTrait
 */
class EventsAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::setEventManager
     * @covers ::attachDefaultListeners
     */
    public function testSetEventManagerInitializesEvents()
    {
        $em = new EventManager();

        $trait = new EventsAware();
        $trait->setEventManager($em);

        $this->assertSame($em, $trait->events());

        $result = $em->fire('foo');
        $this->assertSame('fired', $result->top());
    }

    /**
     * @covers ::events
     * @covers ::setEventManager
     */
    public function testEventsAreLazyLoaded()
    {
        $trait = new EventsAware();
        $this->assertInstanceOf(EventManager::class, $trait->events());
    }
}
