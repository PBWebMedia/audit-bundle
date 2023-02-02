<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\Event;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\LogAuditEvent;

class LogAuditEventTest extends MockeryTestCase
{
    public function test(): void
    {
        $auditEvent = \Mockery::mock(AuditEventInterface::class);
        $event = new LogAuditEvent($auditEvent);
        $this->assertSame($auditEvent, $event->getEvent());
    }
}
