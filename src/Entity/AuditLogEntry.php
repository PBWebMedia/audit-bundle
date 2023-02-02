<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class AuditLogEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    protected ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    protected ?\DateTime $time = null;

    #[ORM\Column(type: 'string', length: 10)]
    protected ?string $level = null;

    #[ORM\Column(type: 'string')]
    protected ?string $name = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    protected ?string $ip = null;

    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $user = null;

    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $impersonatingUser = null;

    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $description = null;

    #[ORM\Column(type: 'json', nullable: true)]
    protected ?array $changeSet = null;

    public function __construct(string $level, string $name)
    {
        $this->level = $level;
        $this->name = $name;
        $this->time = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): \DateTime
    {
        return $this->time;
    }

    public function setTime(\DateTime $time): void
    {
        $this->time = $time;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function setLevel(string $level): void
    {
        $this->level = $level;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
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
