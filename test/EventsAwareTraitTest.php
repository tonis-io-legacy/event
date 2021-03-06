<?php

namespace Tonis\Event;

use Tonis\Event\TestAsset\EventsAware;

/**
 * @coversDefaultClass \Tonis\Event\EventsAwareTrait
 */
class EventsAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::getEventManager
     * @covers ::setEventManager
     */
    public function testEventsAreLazyLoaded()
    {
        $trait = new EventsAware();
        $this->assertInstanceOf(EventManager::class, $trait->getEventManager());
    }
}
