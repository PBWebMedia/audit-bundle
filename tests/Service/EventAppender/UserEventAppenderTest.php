<?php

namespace Tests\Pbweb\AuditBundle\Service\EventAppender;

use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Service\EventAppender\UserEventAppender;
use Prophecy\Argument\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class UserEventAppenderTest
 *
 * @copyright 2016 PB Web Media B.V.
 */
class UserEventAppenderTest extends \PHPUnit_Framework_TestCase
{
    /** @var Mock|TokenStorageInterface */
    protected $tokenStorage;
    /** @var Mock|TokenInterface */
    protected $token;
    /** @var Mock|AuditEventInterface */
    protected $event;
    /** @var UserEventAppender */
    protected $appender;

    public function setUp()
    {
        parent::setUp();

        $this->tokenStorage = \Mockery::mock(TokenStorageInterface::class);
        $this->token = \Mockery::mock(TokenInterface::class);
        $this->event = \Mockery::mock(AuditEventInterface::class);
        $this->appender = new UserEventAppender($this->tokenStorage);

        $this->event->shouldReceive('getUser')->andReturnNull()->byDefault();
        $this->tokenStorage->shouldReceive('getToken')->andReturn($this->token)->byDefault();
        $this->token->shouldReceive('getUsername')->andReturn('foo')->byDefault();
    }

    public function testSetsUser()
    {
        $this->event->shouldReceive('setUser')
            ->once()
            ->with('foo');

        $this->appender->append($this->event);
    }

    public function testIgnoresIfUserSet()
    {
        $this->event->shouldReceive('getUser')->andReturn('foo');
        $this->event->shouldReceive('setUser')->never();

        $this->appender->append($this->event);
    }

    public function testSetsUserToAnonymousIfNoToken()
    {
        $this->tokenStorage->shouldReceive('getToken')->andReturnNull();
        $this->event->shouldReceive('setUser')
            ->once()
            ->with('-');

        $this->appender->append($this->event);
    }
}
