<?php

namespace Pbweb\AuditBundle\Service\EventAppender;

use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\SwitchUserRole;

/**
 * Class ImpersonatingUserEventAppender
 *
 * @copyright 2016 PB Web Media B.V.
 */
class ImpersonatingUserEventAppender implements EventSubscriberInterface
{
    /** @var TokenStorageInterface */
    protected $tokenStorage;
    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::APPEND_EVENT => 'append',
        ];
    }

    public function append(AuditEventInterface $event)
    {
        if ($event->getImpersonatingUser()) {
            return;
        }

        if ( ! $this->tokenStorage->getToken()) {
            return;
        }

        if ( ! $this->authorizationChecker->isGranted('ROLE_PREVIOUS_ADMIN')) {
            return;
        }

        foreach ($this->tokenStorage->getToken()->getRoles() as $role) {
            if ($role instanceof SwitchUserRole) {
                $event->setImpersonatingUser($role->getSource()->getUsername());

                return;
            }
        }
    }
}
