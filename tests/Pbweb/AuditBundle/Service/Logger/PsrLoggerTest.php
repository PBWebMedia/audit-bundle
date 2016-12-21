<?php

namespace Tests\Pbweb\AuditBundle\Service\Logger;

use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Service\Logger\PsrLogger;
use Psr\Log\LoggerInterface;

/**
 * Class PsrLoggerTest
 *
 * @copyright 2016 PB Web Media B.V.
 */
class PsrLoggerTest extends \PHPUnit_Framework_TestCase
{
    /** @var Mock|LoggerInterface */
    protected $innerLogger;
    /** @var Mock|AuditEventInterface */
    protected $event;
    /** @var PsrLogger */
    protected $logger;

    public function setUp()
    {
        parent::setUp();

        $this->innerLogger = \Mockery::mock(LoggerInterface::class);
        $this->event = \Mockery::mock(AuditEventInterface::class);
        $this->logger = new PsrLogger($this->innerLogger);

        $this->event->shouldReceive('getLevel')->andReturn('info')->byDefault();
        $this->event->shouldReceive('getName')->andReturn('foo')->byDefault();
        $this->event->shouldReceive('getDescription')->andReturn('bar')->byDefault();
        $this->event->shouldReceive('getIp')->andReturn('127.0.0.1')->byDefault();
        $this->event->shouldReceive('getUser')->andReturn('bla')->byDefault();
        $this->event->shouldReceive('getImpersonatingUser')->andReturn('bloo')->byDefault();
        $this->event->shouldReceive('getChangeSet')->andReturn(['key' => 'value'])->byDefault();
    }

    public function testLogs()
    {
        $this->innerLogger->shouldReceive('log')
            ->once()
            ->with('info', \Mockery::on(function(string $message) {
                self::assertContains('foo', $message);
                self::assertContains('bar', $message);
                self::assertContains('bla', $message);
                self::assertContains('bloo', $message);
                self::assertContains('127.0.0.1', $message);
                self::assertContains('key', $message);
                self::assertContains('value', $message);

                return true;
            }));

        $this->logger->log($this->event);
    }


}
