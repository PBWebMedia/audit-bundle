<?php

namespace Pbweb\AuditBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Pbweb\AuditBundle\Event\AuditEvent;
use Pbweb\AuditBundle\Service\AuditLog;

/**
 * Class DoctrineEntityEventListener
 *
 * @copyright 2016 PB Web Media B.V.
 */
class DoctrineEntityEventListener
{
    /** @var AuditLog */
    protected $log;

    public function __construct(AuditLog $log)
    {
        $this->log = $log;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $event = new AuditEvent('pbweb_audit.entity_insert');
        $event->setDescription('inserted ' . get_class($args->getEntity()));
        $event->setChangeSet($this->getChangeSet($args));

        $this->log->log($event);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $event = new AuditEvent('pbweb_audit.entity_update');
        $event->setDescription('updated ' . get_class($args->getEntity()));
        $event->setChangeSet($this->getChangeSet($args));

        $this->log->log($event);
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $event = new AuditEvent('pbweb_audit.entity_remove');
        $event->setDescription('removed ' . get_class($args->getEntity()));
        $event->setChangeSet($this->getChangeSet($args));

        $this->log->log($event);
    }

    protected function getChangeSet(LifecycleEventArgs $args): array
    {
        $entity = $args->getEntity();
        $changeSet = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($entity);

        return $changeSet;
    }
}
