<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Service\EventAppender;

use Pbweb\AuditBundle\Event\AppendAuditEvent;
use Pbweb\AuditBundle\Event\EntityAuditEvent;
use Pbweb\AuditBundle\Model\ImpersonatingUserAwareInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class ImpersonatingUserAwareEntityEventAppender implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            AppendAuditEvent::class => 'append',
        ];
    }

    public function append(AppendAuditEvent $appendEvent): void
    {
        $event = $appendEvent->getEvent();
        if ( ! $event instanceof EntityAuditEvent) {
            return;
        }

        $entity = $event->getEntity();
        if ( ! $entity instanceof ImpersonatingUserAwareInterface) {
            return;
        }

        $event->setImpersonatingUser($entity->getImpersonatingUser());
    }
}
