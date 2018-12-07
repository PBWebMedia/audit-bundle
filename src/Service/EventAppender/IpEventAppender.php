<?php

namespace Pbweb\AuditBundle\Service\EventAppender;

use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class IpEventAppender implements EventSubscriberInterface
{
    /** @var RequestStack */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::APPEND_EVENT => 'append',
        ];
    }

    public function append(AuditEventInterface $event)
    {
        if ($event->getIp()) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();
        if ( ! $request) {
            return;
        }

        $event->setIp($request->getClientIp());
    }
}
