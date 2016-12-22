<?php

namespace Tests\Pbweb\AuditBundle\Entity;

use Pbweb\AuditBundle\Entity\AuditLogEntry;

/**
 * Class AuditLogEntryTest
 *
 * @copyright 2016 PB Web Media B.V.
 */
class AuditLogEntryTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $entry = new AuditLogEntry('debug', 'name');
        self::assertEquals('debug', $entry->getLevel());
        self::assertEquals('name', $entry->getName());
        self::assertInstanceOf(\DateTime::class, $entry->getTime());
    }

    public function testGettersSetters()
    {
        $entry = new AuditLogEntry('debug', 'name');

        $now = new \DateTime();
        $entry->setTime($now);
        self::assertEquals($now, $entry->getTime());

        $entry->setLevel('info');
        self::assertEquals('info', $entry->getLevel());

        $entry->setName('name-2');
        self::assertEquals('name-2', $entry->getName());

        $entry->setIp('127.0.0.1');
        self::assertEquals('127.0.0.1', $entry->getIp());

        $entry->setUser('user');
        self::assertEquals('user', $entry->getUser());

        $entry->setImpersonatingUser('impUser');
        self::assertEquals('impUser', $entry->getImpersonatingUser());

        $entry->setDescription('desc');
        self::assertEquals('desc', $entry->getDescription());

        $entry->setChangeSet(['foo' => 'bar']);
        self::assertEquals(['foo' => 'bar'], $entry->getChangeSet());
    }

}
