<?php

namespace Tests\Pbweb\AuditBundle\Service\Logger;

use Mockery\Mock;
use Pbweb\AuditBundle\Service\Logger\DummyLogger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Event;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class DummyLoggerTest extends TestCase
{
    public function testStopsPropagation()
    {
        /** @var Mock|Event $event */
        $event = \Mockery::mock(Event::class);
        $event->shouldReceive('stopPropagation')->once();

        $logger = new DummyLogger();
        $logger->log($event);
    }

    public function testCollectsEvents()
    {
        /** @var Mock|Event $event */
        $event = \Mockery::mock(Event::class);
        $event->shouldIgnoreMissing();

        $logger = new DummyLogger();
        $logger->log($event);

        $this->assertEquals([$event], $logger->getEventList());
    }
}
