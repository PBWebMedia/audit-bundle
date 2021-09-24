<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Service\Logger;

use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\LogAuditEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class DummyLogger implements EventSubscriberInterface
{
    /** @var AuditEventInterface[] */
    private array $eventList = [];

    public static function getSubscribedEvents()
    {
        return [
            LogAuditEvent::class => ['log', 100],
        ];
    }

    public function log(LogAuditEvent $logEvent): void
    {
        $this->eventList[] = $logEvent->getEvent();

        $logEvent->stopPropagation();
    }

    public function getEventList(): array
    {
        return $this->eventList;
    }
}
