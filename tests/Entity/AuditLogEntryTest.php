<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\Entity;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Pbweb\AuditBundle\Entity\AuditLogEntry;

class AuditLogEntryTest extends MockeryTestCase
{
    public function testConstructor(): void
    {
        $entry = new AuditLogEntry('debug', 'name');
        $this->assertEquals('debug', $entry->getLevel());
        $this->assertEquals('name', $entry->getName());
        $this->assertInstanceOf(\DateTime::class, $entry->getTime());
    }

    public function testGettersSetters(): void
    {
        $entry = new AuditLogEntry('debug', 'name');

        $now = new \DateTime();
        $entry->setTime($now);
        $this->assertEquals($now, $entry->getTime());

        $entry->setLevel('info');
        $this->assertEquals('info', $entry->getLevel());

        $entry->setName('name-2');
        $this->assertEquals('name-2', $entry->getName());

        $entry->setIp('127.0.0.1');
        $this->assertEquals('127.0.0.1', $entry->getIp());

        $entry->setUser('user');
        $this->assertEquals('user', $entry->getUser());

        $entry->setImpersonatingUser('impUser');
        $this->assertEquals('impUser', $entry->getImpersonatingUser());

        $entry->setDescription('desc');
        $this->assertEquals('desc', $entry->getDescription());

        $entry->setChangeSet(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $entry->getChangeSet());
    }

}
