<?php

namespace Tests\Pbweb\AuditBundle\Service\EventAppender;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Service\EventAppender\ImpersonatingUserEventAppender;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\SwitchUserRole;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class ImpersonatingUserEventAppenderTest extends MockeryTestCase
{
    /** @var ImpersonatingUserEventAppender */
    private $appender;
    /** @var Mock|TokenStorageInterface */
    private $tokenStorage;
    /** @var Mock|AuthorizationCheckerInterface */
    private $authorizationChecker;

    /** @var Mock|TokenInterface */
    private $token;
    /** @var Mock|SwitchUserRole */
    private $switchRole;
    /** @var Mock|TokenInterface */
    private $switchRoleSource;
    /** @var Mock|AuditEventInterface */
    private $event;

    protected function setUp(): void
    {
        $this->tokenStorage = \Mockery::mock(TokenStorageInterface::class);
        $this->authorizationChecker = \Mockery::mock(AuthorizationCheckerInterface::class);
        $this->appender = new ImpersonatingUserEventAppender($this->tokenStorage, $this->authorizationChecker);

        $this->token = \Mockery::mock(TokenInterface::class);
        $this->switchRole = \Mockery::mock(SwitchUserRole::class);
        $this->switchRoleSource = \Mockery::mock(TokenInterface::class);
        $this->event = \Mockery::mock(AuditEventInterface::class);

        $this->event->shouldReceive('getImpersonatingUser')->andReturnNull()->byDefault();
        $this->tokenStorage->shouldReceive('getToken')->andReturn($this->token)->byDefault();
        $this->authorizationChecker->shouldReceive('isGranted')->andReturn(true)->byDefault();
        $this->token->shouldReceive('getRoles')->andReturn([$this->switchRole])->byDefault();
        $this->switchRole->shouldReceive('getSource')->andReturn($this->switchRoleSource)->byDefault();
        $this->switchRoleSource->shouldReceive('getUsername')->andReturn('foo')->byDefault();
    }

    public function testSetsImpersonatingUser()
    {
        $this->event->shouldReceive('setImpersonatingUser')
            ->once()
            ->with('foo');

        $this->appender->append($this->event);
    }

    public function testIgnoresIfImpersonatingUserIsSet()
    {
        $this->event->shouldReceive('getImpersonatingUser')->andReturn('bla');
        $this->event->shouldReceive('setImpersonatingUser')->never();

        $this->appender->append($this->event);
    }

    public function testIgnoresNoToken()
    {
        $this->tokenStorage->shouldReceive('getToken')->andReturnNull();
        $this->event->shouldReceive('setImpersonatingUser')->never();

        $this->appender->append($this->event);
    }

    public function testIgnoresNotGrantedSwitchRole()
    {
        $this->authorizationChecker->shouldReceive('isGranted')
            ->once()
            ->with('ROLE_PREVIOUS_ADMIN')
            ->andReturnNull();
        $this->event->shouldReceive('setImpersonatingUser')->never();

        $this->appender->append($this->event);
    }
}
