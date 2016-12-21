<?php

namespace Pbweb\AuditBundle\Service\Logger;

use Doctrine\ORM\EntityManagerInterface;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class AbstractDoctrineLogger
 *
 * @copyright 2016 PB Web Media B.V.
 */
abstract class AbstractDoctrineLogger implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::LOG_EVENT => 'log',
        ];
    }

    public function log(AuditEventInterface $event)
    {
        $entity = $this->convertToEntity($event);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    abstract protected function convertToEntity(AuditEventInterface $event);
}
