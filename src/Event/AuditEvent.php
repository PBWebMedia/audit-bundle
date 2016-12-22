<?php

namespace Pbweb\AuditBundle\Event;

use Psr\Log\LogLevel;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class AuditEvent
 *
 * @copyright 2016 PB Web Media B.V.
 */
class AuditEvent extends Event implements AuditEventInterface
{
    /** @var \DateTime */
    protected $time;
    /** @var string */
    protected $name;
    /** @var string */
    protected $level;
    /** @var  string */
    protected $ip;
    /** @var string */
    protected $user;
    /** @var string */
    protected $impersonatingUser;
    /** @var string */
    protected $description;
    /** @var array */
    protected $changeSet;

    public function __construct(string $name, $level = LogLevel::INFO)
    {
        $this->time = new \DateTime();
        $this->name = $name;
        $this->level = $level;
    }

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp(string $ip): AuditEventInterface
    {
        $this->ip = $ip;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(string $user): AuditEventInterface
    {
        $this->user = $user;

        return $this;
    }

    public function getImpersonatingUser()
    {
        return $this->impersonatingUser;
    }

    public function setImpersonatingUser(string $impersonatingUser): AuditEventInterface
    {
        $this->impersonatingUser = $impersonatingUser;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description): AuditEventInterface
    {
        $this->description = $description;

        return $this;
    }

    public function getChangeSet()
    {
        return $this->changeSet;
    }

    public function setChangeSet(array $changeSet): AuditEventInterface
    {
        $this->changeSet = $changeSet;

        return $this;
    }
}
