<?php

namespace Tests\Pbweb\AuditBundle\Event;

use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEntityEvent;
use Pbweb\AuditBundle\Event\AuditEvent;

/**
 * Class AuditEntityEventTest
 *
 * @copyright 2016 PB Web Media B.V.
 */
class AuditEntityEventTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $entity = new Mock();
        $event = new AuditEntityEvent('name', $entity, 'debug');
        self::assertEquals('name', $event->getName());
        self::assertEquals($entity, $event->getEntity());
        self::assertEquals('debug', $event->getLevel());
        self::assertInstanceOf(\DateTime::class, $event->getTime());
    }
}
