<?php

namespace Pbweb\AuditBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Pbweb\AuditBundle\Event\AuditEvent;
use Pbweb\AuditBundle\Service\AuditLog;

/**
 * Class DoctrineEntityEventListener
 *
 * @copyright 2016 PB Web Media B.V.
 */
class DoctrineEntityEventListener implements EventSubscriber
{
    /** @var AuditLog */
    protected $log;
    /** @var string */
    private $logEntityFqdn;

    public function __construct(AuditLog $log, string $logEntityFqdn = '')
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

        $event = new AuditEvent('pbweb_audit.entity_insert');
        $event->setDescription('inserted ' . get_class($args->getEntity()));
        $event->setChangeSet($this->getChangeSet($args));

        $this->log->log($event);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        if ($this->isAuditLogEntity($args)) {
            return;
        }

        $event = new AuditEvent('pbweb_audit.entity_update');
        $event->setDescription('updated ' . get_class($args->getEntity()));
        $event->setChangeSet($this->getChangeSet($args));

        $this->log->log($event);
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        if ($this->isAuditLogEntity($args)) {
            return;
        }

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

    protected function isAuditLogEntity(LifecycleEventArgs $args)
    {
        return get_class($args->getEntity()) == $this->logEntityFqdn;
    }
}
