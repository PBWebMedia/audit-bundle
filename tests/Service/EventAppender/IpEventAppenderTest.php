<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\Service\EventAppender;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Mock;
use Pbweb\AuditBundle\Event\AppendAuditEvent;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Service\EventAppender\IpEventAppender;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class IpEventAppenderTest extends MockeryTestCase
{
    private IpEventAppender $appender;
    private Mock|RequestStack $requestStack;

    private Mock|Request $request;
    private Mock|AppendAuditEvent $appendEvent;
    private Mock|AuditEventInterface $event;

    protected function setUp(): void
    {
        $this->requestStack = \Mockery::mock(RequestStack::class);
        $this->appender = new IpEventAppender($this->requestStack);

        $this->request = \Mockery::mock(Request::class);
        $this->appendEvent = \Mockery::mock(AppendAuditEvent::class);
        $this->event = \Mockery::mock(AuditEventInterface::class);

        $this->appendEvent->shouldReceive('getEvent')->andReturn($this->event)->byDefault();
        $this->event->shouldReceive('getIp')->andReturnNull()->byDefault();
        $this->requestStack->shouldReceive('getCurrentRequest')->andReturn($this->request)->byDefault();
        $this->request->shouldReceive('getClientIp')->andReturn('127.0.0.1')->byDefault();
    }

    public function testSetsIp(): void
    {
        $this->event->shouldReceive('setIp')
            ->once()
            ->with('127.0.0.1');

        $this->appender->append($this->appendEvent);
    }

    public function testIgnoresIfIpSet(): void
    {
        $this->event->shouldReceive('getIp')->andReturn('127.0.0.1');
        $this->event->shouldReceive('setIp')->never();

        $this->appender->append($this->appendEvent);
    }

    public function testIgnoresIfNoRequest(): void
    {
        $this->requestStack->shouldReceive('getCurrentRequest')->andReturnNull();
        $this->event->shouldReceive('setIp')->never();

        $this->appender->append($this->appendEvent);
    }
}
