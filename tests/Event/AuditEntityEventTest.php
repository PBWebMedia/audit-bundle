<?php

namespace Tests\Pbweb\AuditBundle\Event;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEntityEvent;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class AuditEntityEventTest extends MockeryTestCase
{
    public function testConstructor()
    {
        $entity = new Mock();
        $event = new AuditEntityEvent('name', $entity, 'debug');
        $this->assertEquals('name', $event->getName());
        $this->assertEquals($entity, $event->getEntity());
        $this->assertEquals('debug', $event->getLevel());
        $this->assertInstanceOf(\DateTime::class, $event->getTime());
    }
}
