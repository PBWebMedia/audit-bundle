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
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
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
        if ($event->getImpersonatingUser()) {
            return;
        }

        if ( ! $this->tokenStorage->getToken()) {
            return;
        }

        if ( ! $this->authorizationChecker->isGranted('IS_IMPERSONATOR')) {
            return;
        }

        $token = $this->tokenStorage->getToken();
        if ($token instanceof SwitchUserToken) {
            $event->setImpersonatingUser($token->getOriginalToken()->getUsername());
        }
    }
}
