<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\PersistentCollection;
use Pbweb\AuditBundle\Event\EntityAuditEvent;
use Pbweb\AuditBundle\Service\AuditLogInterface;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class DoctrineEntityEventListener implements EventSubscriber
{
    private AuditLogInterface $log;
    private string $logEntityFqdn;

    public function __construct(AuditLogInterface $log, string $logEntityFqdn = '')
    {
        $this->log = $log;
        $this->logEntityFqdn = $logEntityFqdn;
    }

    public function getSubscribedEvents()
    {
        return [
            'postPersist',
            'postUpdate',
            'preRemove',
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        if ($this->isAuditLogEntity($args)) {
            return;
        }

        $event = new EntityAuditEvent('pbweb_audit.entity_insert', $args->getEntity());
        $event->setDescription('inserted ' . get_class($args->getEntity()));
        $event->setChangeSet($this->getChangeSet($args));

        $this->log->log($event);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        if ($this->isAuditLogEntity($args)) {
            return;
        }

        $event = new EntityAuditEvent('pbweb_audit.entity_update', $args->getEntity());
        $event->setDescription('updated ' . get_class($args->getEntity()));
        $event->setChangeSet($this->getChangeSet($args));

        $this->log->log($event);
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        if ($this->isAuditLogEntity($args)) {
            return;
        }

        $event = new EntityAuditEvent('pbweb_audit.entity_remove', $args->getEntity());
        $event->setDescription('removed ' . get_class($args->getEntity()));
        $event->setChangeSet($this->getChangeSet($args));

        $this->log->log($event);
    }

    protected function getChangeSet(LifecycleEventArgs $args): array
    {
        $entity = $args->getEntity();
        $unitOfWork = $args->getEntityManager()->getUnitOfWork();

        return array_merge(
            $unitOfWork->getEntityChangeSet($entity),
            $this->getCollectionChangeSet($entity, $unitOfWork->getScheduledCollectionUpdates())
        );
    }

    protected function getCollectionChangeSet(mixed $entity, array $collectionList): array
    {
        $collectionChangeSet = [];
        foreach ($collectionList as $collection) {
            if ( ! $collection instanceof PersistentCollection) {
                continue;
            }

            if (spl_object_hash($collection->getOwner()) === spl_object_hash($entity)) {
                $collectionChangeSet[$collection->getMapping()['fieldName']] = [
                    'insertions' => $collection->getInsertDiff(),
                    'deletions' => $collection->getDeleteDiff(),
                ];
            }
        }

        return $collectionChangeSet;
    }

    protected function isAuditLogEntity(LifecycleEventArgs $args): bool
    {
        return get_class($args->getEntity()) == $this->logEntityFqdn;
    }
}
