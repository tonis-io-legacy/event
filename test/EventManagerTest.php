<?php

namespace Tonis\Event;

use Tonis\Event\TestAsset\BasicSubscriber;

/**
 * Class EventManagerTest
 * @package Tonis\Event
 *
 * @coversDefaultClass \Tonis\Event\EventManager
 */
class EventManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::subscribe
     */
    public function testSubscribe()
    {
        $em = new EventManager();
        $em->subscribe(new BasicSubscriber());

        $result = $em->fire('foo');

        $this->assertSame('fired', $result->top());
    }

    /**
     * @covers ::fire
     * @covers ::getQueue
     */
    public function testEventsStops()
    {
        $var = null;
        $em = new EventManager();
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
        $em = new EventManager();
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
        $em = new EventManager();
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
        $em = new EventManager();
        $em->on('foo', function() { }, false);
    }

    /**
     * @covers ::clear
     */
    public function testClear()
    {
        $em = new EventManager();
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
        $em = new EventManager();
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
        $em = new EventManager();
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
        $em = new EventManager();
        $fired = false;
        $em->on('foo', function(Event $event) use (&$fired) {
            $fired = true;
        });

        $em->fire('foo');

        $this->assertTrue($fired);
    }

    /**
     * @covers ::fire
     */
    public function testResponseResultIsFirstInFirstOut()
    {
        $em = new EventManager();

        $em->on('foo', function() { return 3; });
        $em->on('foo', function() { return 2; }, 2);
        $em->on('foo', function() { return 1; });

        $response = $em->fire('foo');
        $response->rewind();

        $this->assertInstanceOf('SplQueue', $response);
        $this->assertCount(3, $response);
        $this->assertSame(2, $response->current());
        $response->next();
        $this->assertSame(3, $response->current());
        $response->next();
        $this->assertSame(1, $response->current());
    }
}
