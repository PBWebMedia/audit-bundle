<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\Service\Logger;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\LogAuditEvent;
use Pbweb\AuditBundle\Service\Logger\PsrLogger;
use Psr\Log\LoggerInterface;

class PsrLoggerTest extends MockeryTestCase
{
    private PsrLogger $logger;
    private Mock|LoggerInterface $innerLogger;

    private Mock|LogAuditEvent $logEvent;
    private Mock|AuditEventInterface $event;

    protected function setUp(): void
    {
        $this->innerLogger = \Mockery::mock(LoggerInterface::class);
        $this->logger = new PsrLogger($this->innerLogger);

        $this->logEvent = \Mockery::mock(LogAuditEvent::class);
        $this->event = \Mockery::mock(AuditEventInterface::class);

        $this->logEvent->shouldReceive('getEvent')->andReturn($this->event)->byDefault();

        $this->event->shouldReceive('getLevel')->andReturn('info')->byDefault();
        $this->event->shouldReceive('getName')->andReturn('foo')->byDefault();
        $this->event->shouldReceive('getDescription')->andReturn('bar')->byDefault();
        $this->event->shouldReceive('getIp')->andReturn('127.0.0.1')->byDefault();
        $this->event->shouldReceive('getUser')->andReturn('bla')->byDefault();
        $this->event->shouldReceive('getImpersonatingUser')->andReturn('bloo')->byDefault();
        $this->event->shouldReceive('getChangeSet')->andReturn(['key' => 'value'])->byDefault();
    }

    public function testLogs(): void
    {
        $this->innerLogger->shouldReceive('log')
            ->once()
            ->with('info', \Mockery::on(function (string $message) {
                $this->assertStringContainsString('foo', $message);
                $this->assertStringContainsString('bar', $message);
                $this->assertStringContainsString('bla', $message);
                $this->assertStringContainsString('bloo', $message);
                $this->assertStringContainsString('127.0.0.1', $message);
                $this->assertStringContainsString('key', $message);
                $this->assertStringContainsString('value', $message);

                return true;
            }));

        $this->logger->log($this->logEvent);
    }
}
