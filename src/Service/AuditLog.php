<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Service;

use Pbweb\AuditBundle\Event\AppendAuditEvent;
use Pbweb\AuditBundle\Event\AuditEventInterface;
use Pbweb\AuditBundle\Event\LogAuditEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class AuditLog implements AuditLogInterface
{
    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
    )
    {
    }

    public function log(AuditEventInterface $event): void
    {
        $this->dispatcher->dispatch(new AppendAuditEvent($event));
        $this->dispatcher->dispatch($event);
        $this->dispatcher->dispatch(new LogAuditEvent($event));
    }
}
