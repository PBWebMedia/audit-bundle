<?php declare(strict_types=1);

namespace Tests\Pbweb\AuditBundle\Service\Logger;

use Mockery\Mock;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Service\Logger\AbstractDoctrineLogger;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class DoctrineTestLogger extends AbstractDoctrineLogger
{
    protected function convertToEntity(AuditEventInterface $event): mixed
    {
        return new Mock();
    }
}
