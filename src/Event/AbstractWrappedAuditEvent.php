<?php declare(strict_types=1);


namespace Pbweb\AuditBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

abstract class AbstractWrappedAuditEvent extends Event
{
    public function __construct(
        private readonly AuditEventInterface $event,
    )
    {
    }

    public function getEvent(): AuditEventInterface
    {
        return $this->event;
    }
}
