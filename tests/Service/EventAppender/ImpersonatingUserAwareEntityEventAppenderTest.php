<?php

namespace Tests\Pbweb\AuditBundle\Service\EventAppender;

use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEntityEvent;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Model\ImpersonatingUserAwareInterface;
use Pbweb\AuditBundle\Service\EventAppender\ImpersonatingUserAwareEntityEventAppender;
use PHPUnit\Framework\TestCase;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class ImpersonatingUserAwareEntityEventAppenderTest extends TestCase
{
    /** @var ImpersonatingUserAwareEntityEventAppender */
    private $appender;

    /** @var Mock|AuditEntityEvent */
    private $event;
    /** @var Mock|ImpersonatingUserAwareInterface */
    private $entity;

    public function setUp()
    {
        $this->appender = new ImpersonatingUserAwareEntityEventAppender();

        $this->event = \Mockery::mock(AuditEntityEvent::class);
        $this->entity = \Mockery::mock(ImpersonatingUserAwareInterface::class);

        $this->event->shouldReceive('getEntity')->andReturn($this->entity)->byDefault();
        $this->entity->shouldReceive('getImpersonatingUser')->andReturn('foo')->byDefault();
        $this->event->shouldReceive('setImpersonatingUser')->byDefault();
    }

    public function testSetsImpUser()
    {
        $this->event->shouldReceive('setImpersonatingUser')
            ->once()
            ->with('foo');

        $this->appender->append($this->event);
    }

    public function testIgnoresWrongEvents()
    {
        /** @var Mock|AuditEventInterface $event */
        $event = \Mockery::mock(AuditEventInterface::class);
        $this->event->shouldReceive('setImpersonatingUser')->never();

        $this->appender->append($event);
    }

    public function testIgnoresWrongEntity()
    {
        $entity = new Mock();
        $this->event->shouldReceive('getEntity')->andReturn($entity);
        $this->event->shouldReceive('setImpersonatingUser')->never();

        $this->appender->append($this->event);
    }
}
