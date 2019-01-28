<?php

namespace Tests\Pbweb\AuditBundle\Service\Logger;

use Doctrine\ORM\EntityManagerInterface;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Service\Logger\AbstractDoctrineLogger;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class DoctrineLoggerTest extends MockeryTestCase
{
    /** @var AbstractDoctrineLogger */
    private $logger;
    /** @var Mock|EntityManagerInterface */
    private $entityManager;

    /** @var AuditEventInterface */
    private $event;

    public function setUp()
    {
        $this->entityManager = \Mockery::mock(EntityManagerInterface::class);
        $this->logger = new DoctrineTestLogger($this->entityManager);

        $this->event = \Mockery::mock(AuditEventInterface::class);
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
