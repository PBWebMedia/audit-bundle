<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\Event;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Pbweb\AuditBundle\Event\AppendAuditEvent;
use Pbweb\AuditBundle\Event\AuditEventInterface;

class AppendAuditEventTest extends MockeryTestCase
{
    public function test(): void
    {
        $auditEvent = \Mockery::mock(AuditEventInterface::class);
        $event = new AppendAuditEvent($auditEvent);
        $this->assertSame($auditEvent, $event->getEvent());
    }
}
