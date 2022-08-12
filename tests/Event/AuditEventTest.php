<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\Event;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Pbweb\AuditBundle\Event\AuditEvent;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class AuditEventTest extends MockeryTestCase
{
    public function testConstructor(): void
    {
        $event = new AuditEvent('name', 'debug');
        $this->assertEquals('name', $event->getName());
        $this->assertEquals('debug', $event->getLevel());
        $this->assertInstanceOf(\DateTime::class, $event->getTime());
    }

    public function testGettersSetters(): void
    {
        $event = new AuditEvent('name', 'info');

        $event->setIp('127.0.0.1');
        $this->assertEquals('127.0.0.1', $event->getIp());

        $event->setUser('user');
        $this->assertEquals('user', $event->getUser());

        $event->setImpersonatingUser('impUser');
        $this->assertEquals('impUser', $event->getImpersonatingUser());

        $event->setDescription('description');
        $this->assertEquals('description', $event->getDescription());

        $event->setChangeSet(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $event->getChangeSet());
    }
}
