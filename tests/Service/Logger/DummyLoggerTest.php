<?php

namespace Tests\Pbweb\AuditBundle\Service\Logger;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Mock;
use Pbweb\AuditBundle\Service\Logger\DummyLogger;
use Symfony\Component\EventDispatcher\Event;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class DummyLoggerTest extends MockeryTestCase
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
