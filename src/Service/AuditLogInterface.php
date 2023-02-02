<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Service;

use Pbweb\AuditBundle\Event\AuditEventInterface;

interface AuditLogInterface
{
    public function log(AuditEventInterface $event): void;
}
