<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\Service;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Matcher\IsEqual;
use Mockery\Mock;
use Pbweb\AuditBundle\Event\AppendAuditEvent;
use Pbweb\AuditBundle\Event\AuditEvent;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\LogAuditEvent;
use Pbweb\AuditBundle\Service\AuditLog;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AuditLogTest extends MockeryTestCase
{
    private AuditLog $log;
    private Mock|EventDispatcherInterface $dispatcher;

    private Mock|AuditEventInterface $event;

    protected function setUp(): void
    {
        $this->dispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $this->log = new AuditLog($this->dispatcher);

        $this->event = \Mockery::mock(AuditEvent::class);

        $this->event->shouldReceive('getName')->andReturn('pbw.event')->byDefault();
        $this->dispatcher->shouldReceive('dispatch')->byDefault();
    }

    public function testDispatchFlow(): void
    {
        $this->dispatcher->shouldReceive('dispatch')
            ->with(new IsEqual(new AppendAuditEvent($this->event)))
            ->once()
            ->ordered();
        $this->dispatcher->shouldReceive('dispatch')
            ->with($this->event)
            ->once()
            ->ordered();
        $this->dispatcher->shouldReceive('dispatch')
            ->with(new IsEqual(new LogAuditEvent($this->event)))
            ->once()
            ->ordered();

        $this->log->log($this->event);
    }
}
