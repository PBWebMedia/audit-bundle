<?php

namespace Pbweb\AuditBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class AuditLogEntry
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $time;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
    protected $level;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $ip;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $user;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $impersonatingUser;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var array
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $changeSet;

    public function __construct(string $level, string $name)
    {
        $this->level = $level;
        $this->name = $name;
        $this->time = new \DateTime();
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    public function getTime(): \DateTime
    {
        return $this->time;
    }

    public function setTime(\DateTime $time): AuditLogEntry
    {
        $this->time = $time;

        return $this;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function setLevel(string $level): AuditLogEntry
    {
        $this->level = $level;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): AuditLogEntry
    {
        $this->name = $name;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): AuditLogEntry
    {
        $this->ip = $ip;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): AuditLogEntry
    {
        $this->user = $user;

        return $this;
    }

    public function getImpersonatingUser(): ?string
    {
        return $this->impersonatingUser;
    }

    public function setImpersonatingUser(string $impersonatingUser): AuditLogEntry
    {
        $this->impersonatingUser = $impersonatingUser;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): AuditLogEntry
    {
        $this->description = $description;

        return $this;
    }

    public function getChangeSet(): ?array
    {
        return $this->changeSet;
    }

    public function setChangeSet(array $changeSet): AuditLogEntry
    {
        $this->changeSet = $changeSet;

        return $this;
    }
}
