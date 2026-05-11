<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Pbweb\AuditBundle\Event\EntityAuditEvent;
use Pbweb\AuditBundle\Service\AuditLogInterface;

class DoctrineEntityEventListener implements EventSubscriber
{
    private AuditLogInterface $log;
    private string $logEntityFqdn;

    public function __construct(AuditLogInterface $log, string $logEntityFqdn = '')
    {
        $this->log = $log;
        $this->logEntityFqdn = $logEntityFqdn;
    }

    public function getSubscribedEvents(): array
    {
        return [
            'postPersist',
            'postUpdate',
            'preRemove',
        ];
    }

    /** @param LifecycleEventArgs<EntityManagerInterface> $args */
    public function postPersist(LifecycleEventArgs $args): void
    {
        if ($this->isAuditLogEntity($args)) {
            return;
        }

        $event = new EntityAuditEvent('pbweb_audit.entity_insert', $args->getObject());
        $event->setDescription('inserted ' . get_class($args->getObject()));
        $event->setChangeSet($this->getChangeSet($args));

        $this->log->log($event);
    }

    /** @param LifecycleEventArgs<EntityManagerInterface> $args */
    public function postUpdate(LifecycleEventArgs $args): void
    {
        if ($this->isAuditLogEntity($args)) {
            return;
        }

        $event = new EntityAuditEvent('pbweb_audit.entity_update', $args->getObject());
        $event->setDescription('updated ' . get_class($args->getObject()));
        $event->setChangeSet($this->getChangeSet($args));

        $this->log->log($event);
    }

    /** @param LifecycleEventArgs<EntityManagerInterface> $args */
    public function preRemove(LifecycleEventArgs $args): void
    {
        if ($this->isAuditLogEntity($args)) {
            return;
        }

        $event = new EntityAuditEvent('pbweb_audit.entity_remove', $args->getObject());
        $event->setDescription('removed ' . get_class($args->getObject()));
        $event->setChangeSet($this->getChangeSet($args));

        $this->log->log($event);
    }

    /**
     * @param LifecycleEventArgs<EntityManagerInterface> $args
     * @return array<string, mixed>
     */
    protected function getChangeSet(LifecycleEventArgs $args): array
    {
        $entity = $args->getObject();
        $em = $args->getObjectManager();
        $unitOfWork = $em->getUnitOfWork();

        return array_merge(
            $unitOfWork->getEntityChangeSet($entity),
            $this->getCollectionChangeSet($entity, $unitOfWork->getScheduledCollectionUpdates())
        );
    }

    /**
     * @param array<mixed> $collectionList
     * @return array<string, mixed>
     */
    protected function getCollectionChangeSet(mixed $entity, array $collectionList): array
    {
        $collectionChangeSet = [];
        foreach ($collectionList as $collection) {
            if ( ! $collection instanceof PersistentCollection) {
                continue;
            }

            $owner = $collection->getOwner();
            if ($owner === null) {
                continue;
            }

            if (spl_object_hash($owner) === spl_object_hash($entity)) {
                $collectionChangeSet[$collection->getMapping()['fieldName']] = [
                    'insertions' => $collection->getInsertDiff(),
                    'deletions' => $collection->getDeleteDiff(),
                ];
            }
        }

        return $collectionChangeSet;
    }

    /** @param LifecycleEventArgs<EntityManagerInterface> $args */
    protected function isAuditLogEntity(LifecycleEventArgs $args): bool
    {
        return get_class($args->getObject()) == $this->logEntityFqdn;
    }
}
