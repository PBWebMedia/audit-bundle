<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\Service\EventAppender;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Pbweb\AuditBundle\Event\AppendAuditEvent;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Service\EventAppender\ImpersonatingUserEventAppender;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ImpersonatingUserEventAppenderTest extends MockeryTestCase
{
    private ImpersonatingUserEventAppender $appender;
    private MockInterface&TokenStorageInterface $tokenStorage;
    private MockInterface&AuthorizationCheckerInterface $authorizationChecker;

    private MockInterface&AppendAuditEvent $appendEvent;
    private MockInterface&AuditEventInterface $event;
    private MockInterface&TokenInterface $token;
    private MockInterface&TokenInterface $originalToken;

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

    public function testSetsImpersonatingUser(): void
    {
        $this->event->shouldReceive('setImpersonatingUser')
            ->once()
            ->with('foo');

        $this->appender->append($this->appendEvent);
    }

    public function testIgnoresIfImpersonatingUserIsSet(): void
    {
        $this->event->shouldReceive('getImpersonatingUser')->andReturn('bla');
        $this->event->shouldReceive('setImpersonatingUser')->never();

        $this->appender->append($this->appendEvent);
    }

    public function testIgnoresNoToken(): void
    {
        $this->tokenStorage->shouldReceive('getToken')->andReturnNull();
        $this->event->shouldReceive('setImpersonatingUser')->never();

        $this->appender->append($this->appendEvent);
    }

    public function testIgnoresNotGrantedSwitchRole(): void
    {
        $this->authorizationChecker->shouldReceive('isGranted')
            ->with('IS_IMPERSONATOR')
            ->once()
            ->andReturn(false);
        $this->event->shouldReceive('setImpersonatingUser')->never();

        $this->appender->append($this->appendEvent);
    }
}
