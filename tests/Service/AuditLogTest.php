<?php

namespace Tests\Pbweb\AuditBundle\Service;

use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEvent;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\Events;
use Pbweb\AuditBundle\Service\AuditLog;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class AuditLogTest extends TestCase
{
    /** @var AuditLog */
    private $log;
    /** @var Mock|EventDispatcherInterface */
    private $dispatcher;

    /** @var Mock|AuditEventInterface */
    private $event;

    public function setUp()
    {
        $this->dispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $this->log = new AuditLog($this->dispatcher);

        $this->event = \Mockery::mock(AuditEvent::class);

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
