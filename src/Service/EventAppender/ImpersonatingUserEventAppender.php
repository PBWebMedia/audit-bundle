<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Service\EventAppender;

use Pbweb\AuditBundle\Event\AppendAuditEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class ImpersonatingUserEventAppender implements EventSubscriberInterface
{
    private TokenStorageInterface $tokenStorage;
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
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
        if ($event->getImpersonatingUser()) {
            return;
        }

        if ( ! $this->tokenStorage->getToken()) {
            return;
        }

        if ( ! $this->authorizationChecker->isGranted('ROLE_PREVIOUS_ADMIN')) {
            return;
        }

        $token = $this->tokenStorage->getToken();
        if ($token instanceof SwitchUserToken) {
            $event->setImpersonatingUser($token->getOriginalToken()->getUsername());
        }
    }
}
