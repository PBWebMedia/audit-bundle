<?php

namespace Tests\Pbweb\AuditBundle\Service\Logger;

use Doctrine\ORM\EntityManagerInterface;
use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Service\Logger\AbstractDoctrineLogger;

/**
 * Class DoctrineLoggerTest
 *
 * @copyright 2016 PB Web Media B.V.
 */
class DoctrineLoggerTest extends \PHPUnit_Framework_TestCase
{
    /** @var Mock|EntityManagerInterface */
    protected $entityManager;
    /** @var AuditEventInterface */
    protected $event;
    /** @var AbstractDoctrineLogger */
    protected $logger;

    public function setUp()
    {
        parent::setUp();

        $this->entityManager = \Mockery::mock(EntityManagerInterface::class);
        $this->event = \Mockery::mock(AuditEventInterface::class);
        $this->logger = new DoctrineTestLogger($this->entityManager);
    }

    public function testPersistsAndFlushes()
    {
        $this->entityManager->shouldReceive('persist')
            ->once()
            ->with(\Mockery::type(Mock::class));
        $this->entityManager->shouldReceive('flush')
            ->once();

        $this->logger->log($this->event);
    }
}
