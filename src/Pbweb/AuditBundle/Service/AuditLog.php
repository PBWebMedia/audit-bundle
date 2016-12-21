<?php

namespace Pbweb\AuditBundle\Service;

use Pbweb\AuditBundle\Event\AuditEvent;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class AuditLog
 *
 * @copyright 2016 PB Web Media B.V.
 */
class AuditLog
{
    /** @var EventDispatcherInterface */
    protected $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function log(AuditEventInterface $event)
    {
        $this->dispatcher->dispatch(Events::APPEND_EVENT, $event);
        $this->dispatcher->dispatch($event->getName(), $event);
        $this->dispatcher->dispatch(Events::LOG_EVENT, $event);
    }

    public function logSimple($eventName)
    {
        $event = new AuditEvent($eventName);

        $this->log($event);
    }
}
