<?php

namespace Pbweb\AuditBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\PersistentCollection;
use Pbweb\AuditBundle\Event\AuditEntityEvent;
use Pbweb\AuditBundle\Service\AuditLogInterface;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class DoctrineEntityEventListener implements EventSubscriber
{
    /** @var AuditLogInterface */
    private $log;
    /** @var string */
    private $logEntityFqdn;

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

    public function postPersist(LifecycleEventArgs $args)
    {
        if ($this->isAuditLogEntity($args)) {
            return;
        }

        $event = new AuditEntityEvent('pbweb_audit.entity_insert', $args->getEntity());
        $event->setDescription('inserted ' . get_class($args->getEntity()));
        $event->setChangeSet($this->getChangeSet($args));

        $this->log->log($event);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        if ($this->isAuditLogEntity($args)) {
            return;
        }

        $event = new AuditEntityEvent('pbweb_audit.entity_update', $args->getEntity());
        $event->setDescription('updated ' . get_class($args->getEntity()));
        $event->setChangeSet($this->getChangeSet($args));

        $this->log->log($event);
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        if ($this->isAuditLogEntity($args)) {
            return;
        }

        $event = new AuditEntityEvent('pbweb_audit.entity_remove', $args->getEntity());
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

    protected function getCollectionChangeSet($entity, array $collectionList)
    {
        $collectionChangeSet = [];
        foreach ($collectionList as $collection) {
            if ( ! $collection instanceof PersistentCollection) {
                continue;
            }

            if ($collection->getOwner() == $entity) {
                $collectionChangeSet[$collection->getMapping()['fieldName']] = [
                    'insertions' => $collection->getInsertDiff(),
                    'deletions' => $collection->getDeleteDiff(),
                ];
            }
        }

        return $collectionChangeSet;
    }

    protected function isAuditLogEntity(LifecycleEventArgs $args)
    {
        return get_class($args->getEntity()) == $this->logEntityFqdn;
    }
}
