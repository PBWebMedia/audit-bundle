<?php

namespace Tests\Pbweb\AuditBundle\Service\Logger;

use Pbweb\AuditBundle\Service\Logger\DummyLogger;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class DummyLoggerTest
 *
 * @copyright 2016 PB Web Media B.V.
 */
class DummyLoggerTest extends \PHPUnit_Framework_TestCase
{
    public function testStopsPropagation()
    {
        $event = \Mockery::mock(Event::class);
        $event->shouldReceive('stopPropagation')->once();

        $logger = new DummyLogger();
        $logger->log($event);
    }

    public function testCollectsEvents()
    {
        $event = \Mockery::mock(Event::class);
        $event->shouldIgnoreMissing();

        $logger = new DummyLogger();
        $logger->log($event);

        self::assertEquals([$event], $logger->getEventList());
    }
}
