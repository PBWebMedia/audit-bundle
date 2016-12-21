<?php

namespace Tests\Pbweb\AuditBundle\Service\EventAppender;

use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Service\EventAppender\ImpersonatingUserEventAppender;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\SwitchUserRole;

/**
 * Class ImpersonatingUserEventAppenderTest
 *
 * @copyright 2016 PB Web Media B.V.
 */
class ImpersonatingUserEventAppenderTest extends \PHPUnit_Framework_TestCase
{
    /** @var Mock|TokenStorageInterface */
    protected $tokenStorage;
    /** @var Mock|TokenInterface */
    protected $token;
    /** @var Mock|SwitchUserRole */
    protected $switchRole;
    /** @var Mock|TokenInterface */
    protected $switchRoleSource;
    /** @var Mock|AuthorizationCheckerInterface */
    protected $authorizationChecker;
    /** @var Mock|AuditEventInterface */
    protected $event;
    /** @var ImpersonatingUserEventAppender */
    protected $appender;

    public function setUp()
    {
        parent::setUp();

        $this->tokenStorage = \Mockery::mock(TokenStorageInterface::class);
        $this->token = \Mockery::mock(TokenInterface::class);
        $this->switchRole = \Mockery::mock(SwitchUserRole::class);
        $this->switchRoleSource = \Mockery::mock(TokenInterface::class);
        $this->authorizationChecker = \Mockery::mock(AuthorizationCheckerInterface::class);
        $this->event = \Mockery::mock(AuditEventInterface::class);
        $this->appender = new ImpersonatingUserEventAppender($this->tokenStorage, $this->authorizationChecker);

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
