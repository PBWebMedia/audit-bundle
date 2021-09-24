<?php declare(strict_types=1);


namespace Pbweb\AuditBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * @copyright 2021 PB Web Media B.V.
 */
abstract class AbstractWrappedAuditEvent extends Event
{
    private AuditEventInterface $event;

    public function __construct(AuditEventInterface $event)
    {
        $this->event = $event;
    }

    public function getEvent(): AuditEventInterface
    {
        return $this->event;
    }
}
