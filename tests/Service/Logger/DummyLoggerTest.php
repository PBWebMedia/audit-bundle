<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\Service\Logger;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\LogAuditEvent;
use Pbweb\AuditBundle\Service\Logger\DummyLogger;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class DummyLoggerTest extends MockeryTestCase
{
    private AuditEventInterface $event;

    protected function setUp(): void
    {
        $this->event = \Mockery::mock(AuditEventInterface::class);
    }

    public function testStopsPropagation()
    {
        $event = new LogAuditEvent($this->event);

        $logger = new DummyLogger();
        $logger->log($event);

        $this->assertTrue($event->isPropagationStopped());
    }

    public function testCollectsEvents()
    {
        $event = new LogAuditEvent($this->event);

        $logger = new DummyLogger();
        $logger->log($event);

        $this->assertEquals([$this->event], $logger->getEventList());
    }
}
