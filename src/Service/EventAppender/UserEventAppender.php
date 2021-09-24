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
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
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
        if ($event->getUser()) {
            return;
        }

        $user = '-';

        $token = $this->tokenStorage->getToken();
        if ($token) {
            $user = $token->getUsername();
        }

        $event->setUser($user);
    }
}
