<?php

namespace Tests\Pbweb\AuditBundle\Event;

use Pbweb\AuditBundle\Event\AuditEvent;

/**
 * Class AuditEventTest
 *
 * @copyright 2016 PB Web Media B.V.
 */
class AuditEventTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $event = new AuditEvent('name', 'debug');
        self::assertEquals('name', $event->getName());
        self::assertEquals('debug', $event->getLevel());
    }

    public function testGettersSetters()
    {
        $event = new AuditEvent('name', 'info');

        $event->setIp('127.0.0.1');
        self::assertEquals('127.0.0.1', $event->getIp());

        $event->setUser('user');
        self::assertEquals('user', $event->getUser());

        $event->setImpersonatingUser('impUser');
        self::assertEquals('impUser', $event->getImpersonatingUser());

        $event->setDescription('description');
        self::assertEquals('description', $event->getDescription());

        $event->setChangeSet(['foo' => 'bar']);
        self::assertEquals(['foo' => 'bar'], $event->getChangeSet());
    }

}
