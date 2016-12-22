<?php

namespace Tests\Pbweb\AuditBundle\Service;

use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEvent;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\Events;
use Pbweb\AuditBundle\Service\AuditLog;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class AuditLogTest
 *
 * @copyright 2016 PB Web Media B.V.
 */
class AuditLogTest extends \PHPUnit_Framework_TestCase
{
    /** @var Mock|EventDispatcherInterface */
    protected $dispatcher;
    /** @var Mock|AuditEventInterface */
    protected $event;
    /** @var AuditLog */
    protected $log;

    public function setUp()
    {
        parent::setUp();

        $this->dispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $this->event = \Mockery::mock(AuditEvent::class);
        $this->log = new AuditLog($this->dispatcher);

        $this->event->shouldReceive('getName')->andReturn('pbw.event')->byDefault();
        $this->dispatcher->shouldReceive('dispatch')->byDefault();
    }

    public function testDispatchFlow()
    {
        $this->dispatcher->shouldReceive('dispatch')
            ->once()
            ->with(Events::APPEND_EVENT, $this->event);
        $this->dispatcher->shouldReceive('dispatch')
            ->once()
            ->with('pbw.event', $this->event);
        $this->dispatcher->shouldReceive('dispatch')
            ->once()
            ->with(Events::LOG_EVENT, $this->event);

        $this->log->log($this->event);
    }

    public function testLogSimple()
    {
        $this->dispatcher->shouldReceive('dispatch')
            ->once()
            ->with(Events::APPEND_EVENT, \Mockery::type(AuditEvent::class));
        $this->dispatcher->shouldReceive('dispatch')
            ->once()
            ->with('pbw.simple', \Mockery::type(AuditEvent::class));
        $this->dispatcher->shouldReceive('dispatch')
            ->once()
            ->with(Events::LOG_EVENT, \Mockery::type(AuditEvent::class));

        $this->log->logSimple('pbw.simple');
    }
}
