<?php

namespace Tests\Pbweb\AuditBundle\Service\EventAppender;

use Mockery\Mock;
use Pbweb\AuditBundle\Entity\ImpersonatingUserAwareEntityInterface;
use Pbweb\AuditBundle\Event\AuditEntityEvent;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Service\EventAppender\ImpersonatingUserAwareEntityEventAppender;

/**
 * Class ImpersonatingUserAwareEntityEventAppenderTest
 *
 * @copyright 2016 PB Web Media B.V.
 */
class ImpersonatingUserAwareEntityEventAppenderTest extends \PHPUnit_Framework_TestCase
{
    /** @var Mock|AuditEntityEvent */
    protected $event;
    /** @var Mock|ImpersonatingUserAwareEntityInterface */
    protected $entity;
    /** @var ImpersonatingUserAwareEntityEventAppender */
    protected $appender;

    public function setUp()
    {
        parent::setUp();

        $this->event = \Mockery::mock(AuditEntityEvent::class);
        $this->entity = \Mockery::mock(ImpersonatingUserAwareEntityInterface::class);
        $this->appender = new ImpersonatingUserAwareEntityEventAppender();

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
