<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\UnitOfWork;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\EventListener\DoctrineEntityEventListener;
use Pbweb\AuditBundle\Service\AuditLogInterface;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class DoctrineEntityEventListenerTest extends MockeryTestCase
{
    private DoctrineEntityEventListener $listener;
    private Mock|AuditLogInterface $log;

    private Mock|LifecycleEventArgs $args;
    private Mock|\stdClass $entity;
    private Mock|EntityManagerInterface $entityManager;
    private Mock|UnitOfWork $unitOfWork;

    protected function setUp(): void
    {
        $this->log = \Mockery::mock(AuditLogInterface::class);
        $this->listener = new DoctrineEntityEventListener($this->log);

        $this->args = \Mockery::mock(LifecycleEventArgs::class);
        $this->entity = \Mockery::mock(\stdClass::class);
        $this->entityManager = \Mockery::mock(EntityManagerInterface::class);
        $this->unitOfWork = \Mockery::mock(UnitOfWork::class);

        $this->args->shouldReceive('getEntity')->andReturn($this->entity)->byDefault();
        $this->args->shouldReceive('getEntityManager')->andReturn($this->entityManager)->byDefault();
        $this->entityManager->shouldIgnoreMissing($this->entityManager);
        $this->entityManager->shouldReceive('getUnitOfWork')->andReturn($this->unitOfWork)->byDefault();
        $this->unitOfWork->shouldReceive('getEntityChangeSet')->andReturn(['foo' => 'bar'])->byDefault();
        $this->unitOfWork->shouldReceive('getScheduledCollectionUpdates')->andReturn([])->byDefault();
        $this->log->shouldReceive('log')->byDefault();
    }

    public function testPostPersistLogs()
    {
        $this->log->shouldReceive('log')
            ->once()
            ->with(\Mockery::on(function (AuditEventInterface $event) {
                $this->assertStringContainsString('insert', $event->getDescription());
                $this->assertEquals(['foo' => 'bar'], $event->getChangeSet());

                return true;
            }));

        $this->listener->postPersist($this->args);
    }

    public function testPostUpdateLogs()
    {
        $this->log->shouldReceive('log')
            ->once()
            ->with(\Mockery::on(function (AuditEventInterface $event) {
                $this->assertStringContainsString('update', $event->getDescription());
                $this->assertEquals(['foo' => 'bar'], $event->getChangeSet());

                return true;
            }));

        $this->listener->postUpdate($this->args);
    }

    public function testPreRemoveLogs()
    {
        $this->log->shouldReceive('log')
            ->once()
            ->with(\Mockery::on(function (AuditEventInterface $event) {
                $this->assertStringContainsString('remove', $event->getDescription());
                $this->assertEquals(['foo' => 'bar'], $event->getChangeSet());

                return true;
            }));

        $this->listener->preRemove($this->args);
    }

    public function testIgnoresConstructorLogEntityFqdn()
    {
        $listener = new DoctrineEntityEventListener($this->log, get_class($this->entity));

        $this->log->shouldReceive('log')->never();
        $listener->postPersist($this->args);
        $listener->postUpdate($this->args);
        $listener->preRemove($this->args);
    }
}
