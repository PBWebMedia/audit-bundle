<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Event;

use Psr\Log\LogLevel;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @copyright 2016 PB Web Media B.V.
 */
class AuditEvent extends Event implements AuditEventInterface
{
    protected \DateTime $time;
    protected string $name;
    protected string $level;
    protected ?string $ip = null;
    protected ?string $user = null;
    protected ?string $impersonatingUser = null;
    protected ?string $description = null;
    protected ?array $changeSet = null;

    public function __construct(string $name, $level = LogLevel::INFO)
    {
        $this->time = new \DateTime();
        $this->name = $name;
        $this->level = $level;
    }

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

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    public function getImpersonatingUser(): ?string
    {
        return $this->impersonatingUser;
    }

    public function setImpersonatingUser(string $impersonatingUser): void
    {
        $this->impersonatingUser = $impersonatingUser;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getChangeSet(): ?array
    {
        return $this->changeSet;
    }

    public function setChangeSet(array $changeSet): void
    {
        $this->changeSet = $changeSet;
    }
}
