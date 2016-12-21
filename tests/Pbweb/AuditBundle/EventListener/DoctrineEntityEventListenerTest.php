<?php

namespace Tests\Pbweb\AuditBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\EventListener\DoctrineEntityEventListener;
use Pbweb\AuditBundle\Service\AuditLog;

/**
 * Class DoctrineEntityEventListenerTest
 *
 * @copyright 2016 PB Web Media B.V.
 */
class DoctrineEntityEventListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @var Mock|AuditLog */
    protected $log;
    /** @var Mock|LifecycleEventArgs */
    protected $args;
    /** @var Mock */
    protected $entity;
    /** @var Mock|EntityManagerInterface */
    protected $entityManager;
    /** @var DoctrineEntityEventListener */
    protected $listener;

    public function setUp()
    {
        parent::setUp();

        $this->log = \Mockery::mock(AuditLog::class);
        $this->args = \Mockery::mock(LifecycleEventArgs::class);
        $this->entity = \Mockery::mock();
        $this->entityManager = \Mockery::mock(EntityManagerInterface::class);
        $this->listener = new DoctrineEntityEventListener($this->log);

        $this->args->shouldReceive('getEntity')->andReturn($this->entity)->byDefault();
        $this->args->shouldReceive('getEntityManager')->andReturn($this->entityManager)->byDefault();
        $this->entityManager->shouldIgnoreMissing($this->entityManager);
        $this->entityManager->shouldReceive('getEntityChangeSet')->andReturn(
            ['foo' => 'bar']
        )->byDefault();
        $this->log->shouldReceive('log')->byDefault();
    }

    public function testPostPersistLogs()
    {
        $this->log->shouldReceive('log')
            ->once()
            ->with(\Mockery::on(function(AuditEventInterface $event) {
                self::assertContains('insert', $event->getDescription());
                self::assertEquals(['foo' => 'bar'], $event->getChangeSet());

                return true;
            }));

        $this->listener->postPersist($this->args);
    }

    public function testPostUpdateLogs()
    {
        $this->log->shouldReceive('log')
            ->once()
            ->with(\Mockery::on(function(AuditEventInterface $event) {
                self::assertContains('update', $event->getDescription());
                self::assertEquals(['foo' => 'bar'], $event->getChangeSet());

                return true;
            }));

        $this->listener->postUpdate($this->args);
    }

    public function testPreRemoveLogs()
    {
        $this->log->shouldReceive('log')
            ->once()
            ->with(\Mockery::on(function(AuditEventInterface $event) {
                self::assertContains('remove', $event->getDescription());
                self::assertEquals(['foo' => 'bar'], $event->getChangeSet());

                return true;
            }));

        $this->listener->preRemove($this->args);
    }
}
