<?php

namespace Service\EventAppender;

use Entity\ImpersonatingUserAwareEntityInterface;
use Event\AuditEntityEvent;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ImpersonatingUserAwareEntityEventAppender
 *
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
        if(!$entity instanceof ImpersonatingUserAwareEntityInterface) {
            return;
        }

        $event->setImpersonatingUser($entity->getImpersonatingUser());
    }
}
