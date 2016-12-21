<?php

namespace Pbweb\AuditBundle\Service\EventAppender;

use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class UserEventAppender
 *
 * @copyright 2016 PB Web Media B.V.
 */
class UserEventAppender implements EventSubscriberInterface
{
    /** @var TokenStorageInterface */
    protected $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::APPEND_EVENT => 'append',
        ];
    }

    public function append(AuditEventInterface $event)
    {
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
