<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Service\EventAppender;

use Pbweb\AuditBundle\Event\AppendAuditEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class IpEventAppender implements EventSubscriberInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AppendAuditEvent::class => 'append',
        ];
    }

    public function append(AppendAuditEvent $appendEvent): void
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
