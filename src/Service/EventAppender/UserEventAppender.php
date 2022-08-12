<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Service\EventAppender;

use Pbweb\AuditBundle\Event\AppendAuditEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class UserEventAppender implements EventSubscriberInterface
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
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
        if ($event->getUser()) {
            return;
        }

        $user = '-';

        $token = $this->tokenStorage->getToken();
        if ($token) {
            $user = $token->getUserIdentifier();
        }

        $event->setUser($user);
    }
}
