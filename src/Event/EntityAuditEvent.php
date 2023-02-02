<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Event;

use Psr\Log\LogLevel;

class EntityAuditEvent extends AuditEvent
{
    protected mixed $entity;

    public function __construct(string $name, mixed $entity, string $level = LogLevel::INFO)
    {
        parent::__construct($name, $level);

        $this->entity = $entity;
    }

    public function getEntity(): mixed
    {
        return $this->entity;
    }
}
