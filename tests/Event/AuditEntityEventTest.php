<?php

namespace Tests\Pbweb\AuditBundle\Event;

use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEntityEvent;
use PHPUnit\Framework\TestCase;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class AuditEntityEventTest extends TestCase
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
