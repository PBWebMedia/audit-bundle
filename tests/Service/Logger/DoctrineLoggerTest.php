<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\Service\Logger;

use Doctrine\ORM\EntityManagerInterface;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\LogAuditEvent;
use Pbweb\AuditBundle\Service\Logger\AbstractDoctrineLogger;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class DoctrineLoggerTest extends MockeryTestCase
{
    private AbstractDoctrineLogger $logger;
    private Mock|EntityManagerInterface $entityManager;

    private Mock|LogAuditEvent $logEvent;
    private Mock|AuditEventInterface $event;

    protected function setUp(): void
    {
        $this->entityManager = \Mockery::mock(EntityManagerInterface::class);
        $this->logger = new DoctrineTestLogger($this->entityManager);

        $this->logEvent = \Mockery::mock(LogAuditEvent::class);
        $this->event = \Mockery::mock(AuditEventInterface::class);

        $this->logEvent->shouldReceive('getEvent')->andReturn($this->event)->byDefault();
    }

    public function testPersistsAndFlushes(): void
    {
        $this->entityManager->shouldReceive('persist')
            ->once()
            ->with(\Mockery::type(Mock::class));
        $this->entityManager->shouldReceive('flush')
            ->once();

        $this->logger->log($this->logEvent);
    }
}
