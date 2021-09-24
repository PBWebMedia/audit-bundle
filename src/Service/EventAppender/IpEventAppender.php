<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Service\EventAppender;

use Pbweb\AuditBundle\Event\AppendAuditEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class IpEventAppender implements EventSubscriberInterface
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            AppendAuditEvent::class => 'append',
        ];
    }

    public function append(AppendAuditEvent $appendEvent)
    {
        $event = $appendEvent->getEvent();
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
