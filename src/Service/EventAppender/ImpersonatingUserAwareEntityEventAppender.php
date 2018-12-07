<?php

namespace Pbweb\AuditBundle\Service\EventAppender;

use Pbweb\AuditBundle\Event\AuditEntityEvent;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\Events;
use Pbweb\AuditBundle\Model\ImpersonatingUserAwareInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class ImpersonatingUserAwareEntityEventAppender implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            Events::APPEND_EVENT => 'append',
        ];
    }

    public function append(AuditEventInterface $event)
    {
        if ( ! $event instanceof AuditEntityEvent) {
            return;
        }

        $entity = $event->getEntity();
        if ( ! $entity instanceof ImpersonatingUserAwareInterface) {
            return;
        }

        $event->setImpersonatingUser($entity->getImpersonatingUser());
    }
}
