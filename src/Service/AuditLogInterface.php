<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Service;

use Pbweb\AuditBundle\Event\AuditEventInterface;

/**
 * @copyright 2018 PB Web Media B.V.
 */
interface AuditLogInterface
{
    public function log(AuditEventInterface $event): void;
}
