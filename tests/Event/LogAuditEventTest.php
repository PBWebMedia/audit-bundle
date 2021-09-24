<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\Event;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\LogAuditEvent;

/**
 * @copyright 2021 PB Web Media B.V.
 */
class LogAuditEventTest extends MockeryTestCase
{
    public function test()
    {
        /** @var Mock|AuditEventInterface */
        $auditEvent = \Mockery::mock(AuditEventInterface::class);
        $event = new LogAuditEvent($auditEvent);
        $this->assertSame($auditEvent, $event->getEvent());
    }
}
