<?php

namespace Pbweb\AuditBundle\Event;

use Psr\Log\LogLevel;

/**
 * Class AuditEntityEvent
 *
 * @copyright 2016 PB Web Media B.V.
 */
class AuditEntityEvent extends AuditEvent
{
    /** @var  mixed */
    protected $entity;

    public function __construct($name, $entity, $level = LogLevel::INFO)
    {
        parent::__construct($name, $level);

        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }
}
