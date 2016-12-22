<?php

namespace Pbweb\AuditBundle\Service\Logger;

use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\Events;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DummyLogger
 *
 * @copyright 2016 PB Web Media B.V.
 */
class DummyLogger implements EventSubscriberInterface
{
    /** @var AuditEventInterface[] */
    protected $eventList = [];

    public static function getSubscribedEvents()
    {
        return [
            Events::LOG_EVENT => ['log', 100],
        ];
    }

    public function log(Event $event)
    {
        $this->eventList[] = $event;

        $event->stopPropagation();
    }

    public function getEventList()
    {
        return $this->eventList;
    }
}
