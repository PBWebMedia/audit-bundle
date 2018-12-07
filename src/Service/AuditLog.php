<?php

namespace Pbweb\AuditBundle\Service;

use Pbweb\AuditBundle\Event\AuditEvent;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class AuditLog implements AuditLogInterface
{
    /** @var EventDispatcherInterface */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function log(AuditEventInterface $event): void
    {
        $this->dispatcher->dispatch(Events::APPEND_EVENT, $event);
        $this->dispatcher->dispatch($event->getName(), $event);
        $this->dispatcher->dispatch(Events::LOG_EVENT, $event);
    }

    public function logSimple(string $eventName): void
    {
        $event = new AuditEvent($eventName);

        $this->log($event);
    }
}
