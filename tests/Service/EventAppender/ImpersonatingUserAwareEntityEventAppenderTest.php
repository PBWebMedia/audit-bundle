<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\Service\EventAppender;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Mock;
use Pbweb\AuditBundle\Event\AppendAuditEvent;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\EntityAuditEvent;
use Pbweb\AuditBundle\Model\ImpersonatingUserAwareInterface;
use Pbweb\AuditBundle\Service\EventAppender\ImpersonatingUserAwareEntityEventAppender;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class ImpersonatingUserAwareEntityEventAppenderTest extends MockeryTestCase
{
    private ImpersonatingUserAwareEntityEventAppender $appender;

    private Mock|AppendAuditEvent $appendEvent;
    private Mock|EntityAuditEvent $event;
    private Mock|ImpersonatingUserAwareInterface $entity;

    protected function setUp(): void
    {
        $this->appender = new ImpersonatingUserAwareEntityEventAppender();

        $this->appendEvent = \Mockery::mock(AppendAuditEvent::class);
        $this->event = \Mockery::mock(EntityAuditEvent::class);
        $this->entity = \Mockery::mock(ImpersonatingUserAwareInterface::class);

        $this->appendEvent->shouldReceive('getEvent')->andReturn($this->event)->byDefault();
        $this->event->shouldReceive('getEntity')->andReturn($this->entity)->byDefault();
        $this->entity->shouldReceive('getImpersonatingUser')->andReturn('foo')->byDefault();
        $this->event->shouldReceive('setImpersonatingUser')->byDefault();
    }

    public function testSetsImpUser()
    {
        $this->event->shouldReceive('setImpersonatingUser')
            ->once()
            ->with('foo');

        $this->appender->append($this->appendEvent);
    }

    public function testIgnoresWrongEvents()
    {
        /** @var Mock|AuditEventInterface $event */
        $event = \Mockery::mock(AuditEventInterface::class);
        $this->appendEvent->shouldReceive('getEvent')->andReturn($event);
        $this->event->shouldReceive('setImpersonatingUser')->never();

        $this->appender->append($this->appendEvent);
    }

    public function testIgnoresWrongEntity()
    {
        $entity = new Mock(\stdClass::class);
        $this->event->shouldReceive('getEntity')->andReturn($entity);
        $this->event->shouldReceive('setImpersonatingUser')->never();

        $this->appender->append($this->appendEvent);
    }
}
