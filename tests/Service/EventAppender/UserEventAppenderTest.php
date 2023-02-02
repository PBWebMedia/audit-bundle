<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\Service\EventAppender;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Mock;
use Pbweb\AuditBundle\Event\AppendAuditEvent;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Service\EventAppender\UserEventAppender;
use Prophecy\Argument\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserEventAppenderTest extends MockeryTestCase
{
    private UserEventAppender $appender;
    private Mock|TokenStorageInterface $tokenStorage;

    private Mock|TokenInterface $token;
    private Mock|AppendAuditEvent $appendEvent;
    private Mock|AuditEventInterface $event;

    protected function setUp(): void
    {
        $this->tokenStorage = \Mockery::mock(TokenStorageInterface::class);
        $this->appender = new UserEventAppender($this->tokenStorage);

        $this->token = \Mockery::mock(TokenInterface::class);
        $this->appendEvent = \Mockery::mock(AppendAuditEvent::class);
        $this->event = \Mockery::mock(AuditEventInterface::class);

        $this->appendEvent->shouldReceive('getEvent')->andReturn($this->event)->byDefault();
        $this->event->shouldReceive('getUser')->andReturnNull()->byDefault();
        $this->tokenStorage->shouldReceive('getToken')->andReturn($this->token)->byDefault();
        $this->token->shouldReceive('getUserIdentifier')->andReturn('foo')->byDefault();
    }

    public function testSetsUser(): void
    {
        $this->event->shouldReceive('setUser')
            ->once()
            ->with('foo');

        $this->appender->append($this->appendEvent);
    }

    public function testIgnoresIfUserSet(): void
    {
        $this->event->shouldReceive('getUser')->andReturn('foo');
        $this->event->shouldReceive('setUser')->never();

        $this->appender->append($this->appendEvent);
    }

    public function testSetsUserToAnonymousIfNoToken(): void
    {
        $this->tokenStorage->shouldReceive('getToken')->andReturnNull();
        $this->event->shouldReceive('setUser')
            ->once()
            ->with('-');

        $this->appender->append($this->appendEvent);
    }
}
