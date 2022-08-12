<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Service\Logger;

use Doctrine\ORM\EntityManagerInterface;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\LogAuditEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @copyright 2016 PB Web Media B.V.
 */
abstract class AbstractDoctrineLogger implements EventSubscriberInterface
{
    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogAuditEvent::class => 'log',
        ];
    }

    public function log(LogAuditEvent $logEvent): void
    {
        $entity = $this->convertToEntity($logEvent->getEvent());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    abstract protected function convertToEntity(AuditEventInterface $event): mixed;
}
