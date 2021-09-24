<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\Service\EventAppender;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Mock;
use Pbweb\AuditBundle\Event\AppendAuditEvent;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Service\EventAppender\ImpersonatingUserEventAppender;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class ImpersonatingUserEventAppenderTest extends MockeryTestCase
{
    private ImpersonatingUserEventAppender $appender;
    private Mock|TokenStorageInterface $tokenStorage;
    private Mock|AuthorizationCheckerInterface $authorizationChecker;

    private Mock|AppendAuditEvent $appendEvent;
    private Mock|AuditEventInterface $event;
    private Mock|TokenInterface $token;
    private Mock|TokenInterface $originalToken;

    protected function setUp(): void
    {
        $this->tokenStorage = \Mockery::mock(TokenStorageInterface::class);
        $this->authorizationChecker = \Mockery::mock(AuthorizationCheckerInterface::class);
        $this->appender = new ImpersonatingUserEventAppender($this->tokenStorage, $this->authorizationChecker);

        $this->token = \Mockery::mock(SwitchUserToken::class);
        $this->originalToken = \Mockery::mock(SwitchUserToken::class);
        $this->appendEvent = \Mockery::mock(AppendAuditEvent::class);
        $this->event = \Mockery::mock(AuditEventInterface::class);

        $this->appendEvent->shouldReceive('getEvent')->andReturn($this->event)->byDefault();
        $this->event->shouldReceive('getImpersonatingUser')->andReturnNull()->byDefault();
        $this->tokenStorage->shouldReceive('getToken')->andReturn($this->token)->byDefault();
        $this->authorizationChecker->shouldReceive('isGranted')->andReturn(true)->byDefault();
        $this->token->shouldReceive('getOriginalToken')->andReturn($this->originalToken)->byDefault();
        $this->originalToken->shouldReceive('getUsername')->andReturn('foo')->byDefault();
    }

    public function testSetsImpersonatingUser()
    {
        $this->event->shouldReceive('setImpersonatingUser')
            ->once()
            ->with('foo');

        $this->appender->append($this->appendEvent);
    }

    public function testIgnoresIfImpersonatingUserIsSet()
    {
        $this->event->shouldReceive('getImpersonatingUser')->andReturn('bla');
        $this->event->shouldReceive('setImpersonatingUser')->never();

        $this->appender->append($this->appendEvent);
    }

    public function testIgnoresNoToken()
    {
        $this->tokenStorage->shouldReceive('getToken')->andReturnNull();
        $this->event->shouldReceive('setImpersonatingUser')->never();

        $this->appender->append($this->appendEvent);
    }

    public function testIgnoresNotGrantedSwitchRole()
    {
        $this->authorizationChecker->shouldReceive('isGranted')
            ->with('ROLE_PREVIOUS_ADMIN')
            ->once()
            ->andReturnNull();
        $this->event->shouldReceive('setImpersonatingUser')->never();

        $this->appender->append($this->appendEvent);
    }
}
