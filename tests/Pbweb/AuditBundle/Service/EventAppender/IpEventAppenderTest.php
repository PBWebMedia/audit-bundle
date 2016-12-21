<?php

namespace Tests\Pbweb\AuditBundle\Service\EventAppender;

use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Service\EventAppender\IpEventAppender;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class IpEventAppenderTest
 *
 * @copyright 2016 PB Web Media B.V.
 */
class IpEventAppenderTest extends \PHPUnit_Framework_TestCase
{
    /** @var Mock|RequestStack */
    protected $requestStack;
    /** @var Mock|Request */
    protected $request;
    /** @var Mock|AuditEventInterface */
    protected $event;
    /** @var IpEventAppender */
    protected $appender;

    public function setUp()
    {
        parent::setUp();

        $this->requestStack = \Mockery::mock(RequestStack::class);
        $this->request = \Mockery::mock(Request::class);
        $this->event = \Mockery::mock(AuditEventInterface::class);
        $this->appender = new IpEventAppender($this->requestStack);

        $this->event->shouldReceive('getIp')->andReturnNull()->byDefault();
        $this->requestStack->shouldReceive('getCurrentRequest')->andReturn($this->request)->byDefault();
        $this->request->shouldReceive('getClientIp')->andReturn('127.0.0.1')->byDefault();
    }

    public function testSetsIp()
    {
        $this->event->shouldReceive('setIp')
            ->once()
            ->with('127.0.0.1');

        $this->appender->append($this->event);
    }

    public function testIgnoresIfIpSet()
    {
        $this->event->shouldReceive('getIp')->andReturn('127.0.0.1');
        $this->event->shouldReceive('setIp')->never();

        $this->appender->append($this->event);
    }

    public function testIgnoresIfNoRequest()
    {
        $this->requestStack->shouldReceive('getCurrentRequest')->andReturnNull();
        $this->event->shouldReceive('setIp')->never();

        $this->appender->append($this->event);
    }
}
