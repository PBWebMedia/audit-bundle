<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Service\Logger;

use Pbweb\AuditBundle\Event\LogAuditEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PsrLogger implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
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
        $event = $logEvent->getEvent();
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
