<?php

namespace Tonis\Event;

use Tonis\Event\TestAsset\BasicPlugin;
use Tonis\Event\TestAsset\BasicSubscriber;
use Tonis\Event\TestAsset\EventWithNoName;

/**
 * Class EventManagerTest
 * @package Tonis\Event
 *
 * @coversDefaultClass \Tonis\Event\Manager
 */
class EventManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::subscribe
     */
    public function testSubscribe()
    {
        $em = new Manager();
        $em->subscribe(new BasicSubscriber());

        $result = $em->fire('foo');

        $this->assertSame('fired', $result->top());
    }

    /**
     * @covers ::fire
     * @covers ::getQueue
     * @covers \Tonis\Event\Exception\MissingNameException
     * @expectedException \Tonis\Event\Exception\MissingNameException
     * @expectedExceptionMessage Event given but no name specified
     */
    public function testExceptionThrownForMissingName()
    {
        $event = new EventWithNoName();
        $em = new Manager();
        $em->fire($event);
    }

    /**
     * @covers ::fire
     * @covers \Tonis\Event\Exception\SubscriberException
     * @expectedException \Tonis\Event\Exception\SubscriberException
     * @expectedExceptionMessage Error: exception while firing "foo" caught from
     */
    public function testExceptionsAreRethrown()
    {
        $em = new Manager();
        $em->on('foo', function() { throw new \RuntimeException; });
        $em->fire('foo');
    }

    /**
     * @covers ::fire
     * @covers ::getQueue
     */
    public function testEventsStops()
    {
        $var = null;
        $em = new Manager();
        $em->on('foo', function($e) use (&$var) {
            $var = 'foo';
            $e->stop();
        });
        $em->on('foo', function() use (&$var) {
            $var = 'bar';
        });

        $em->fire('foo');
        $this->assertSame('foo', $var);
    }

    /**
     * @covers ::on
     * @covers ::getListeners
     */
    public function testOnAddsEventsAndAreRetrievedProperly()
    {
        $em = new Manager();
        $em->on('foo', function() { echo 'bar'; });

        $this->assertCount(1, $em->getListeners());
        $this->assertCount(1, $em->getListeners('foo'));
        $this->assertCount(0, $em->getListeners('bar'));
    }

    /**
     * @covers ::on
     * @covers \Tonis\Event\Exception\InvalidCallableException::__construct
     * @expectedException \Tonis\Event\Exception\InvalidCallableException
     * @expectedExceptionMessage Invalid argument: expected callable but received boolean
     */
    public function testOnThrowsExceptionForNonCallable()
    {
        $em = new Manager();
        $em->on('foo', false);
    }

    /**
     * @covers ::on
     * @covers \Tonis\Event\Exception\InvalidPriorityException::__construct
     * @expectedException \Tonis\Event\Exception\InvalidPriorityException
     * @expectedExceptionMessage Invalid argument: expected integer but received boolean
     */
    public function testOnThrowsExceptionForInvalidPriorities()
    {
        $em = new Manager();
        $em->on('foo', function() { }, false);
    }

    /**
     * @covers ::clear
     */
    public function testClear()
    {
        $em = new Manager();
        $em->on('foo', function() { echo 'foo'; });
        $em->on('bar', function() { echo 'bar'; });
        $this->assertCount(2, $em->getListeners());

        $em->clear('foo');
        $this->assertCount(1, $em->getListeners('bar'));
        $this->assertCount(1, $em->getListeners());
        $this->assertCount(0, $em->getListeners('foo'));

        $em->clear();
        $this->assertCount(0, $em->getListeners('bar'));
        $this->assertCount(0, $em->getListeners());
    }

    /**
     * @covers ::fire
     * @covers ::getQueue
     */
    public function testFireCreatesEventIfNotGiven()
    {
        $em = new Manager();
        $response = $em->fire('foo');

        $this->assertInstanceOf('SplQueue', $response);
    }

    /**
     * Regression prevention.
     *
     * @covers ::fire
     */
    public function testFireClonesPluginQueue()
    {
        $em = new Manager();
        $em->on('foo', function() { });

        $this->assertCount(1, $em->getListeners('foo'));
        $em->fire('foo');
        $this->assertCount(1, $em->getListeners('foo'));
    }

    /**
     * @covers ::fire
     */
    public function testFireUsingAnEvent()
    {
        $event = new Event('foo', 'test');
        $em = new Manager();
        $fired = false;
        $em->on('foo', function(Event $event) use (&$fired) {
            $fired = true;
        });

        $em->fire($event);

        $this->assertSame('foo', $event->getName());
        $this->assertTrue($fired);
    }

    /**
     * @covers ::fire
     */
    public function testResponseResultIsFirstInFirstOut()
    {
        $em = new Manager();
        $event = new Event('foo');

        $em->on('foo', function() { return 3; });
        $em->on('foo', function() { return 2; }, 2);
        $em->on('foo', function() { return 1; });

        $response = $em->fire($event);
        $response->rewind();

        $this->assertInstanceOf('SplQueue', $response);
        $this->assertCount(3, $response);
        $this->assertSame(2, $response->current());
        $response->next();
        $this->assertSame(3, $response->current());
        $response->next();
        $this->assertSame(1, $response->current());
    }

    /**
     * @covers ::fire
     * @expectedException \Tonis\Event\Exception\MissingNameException
     * @expectedExceptionMessage Event given but no name specified
     */
    public function testFiringEventWithNullNameThrowsException()
    {
        $event = new Event(null);
        $em = new Manager();
        $em->fire($event);
    }
}
