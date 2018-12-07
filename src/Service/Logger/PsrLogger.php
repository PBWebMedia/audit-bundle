<?php

namespace Pbweb\AuditBundle\Service\Logger;

use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\Events;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class PsrLogger implements EventSubscriberInterface
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::LOG_EVENT => 'log',
        ];
    }

    public function log(AuditEventInterface $event)
    {
        $details = [
            'name' => $event->getName(),
            'description' => $event->getDescription(),
            'ip' => $event->getIp(),
            'user' => $event->getUser(),
            'impersonatingUser' => $event->getImpersonatingUser(),
            'changeSet' => $event->getChangeSet(),
        ];

        $this->logger->log($event->getLevel(), 'pbweb_audit: ' . json_encode($details));
    }
}
