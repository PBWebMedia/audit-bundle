<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Event;

/**
 * @copyright 2016 PB Web Media B.V.
 */
interface AuditEventInterface
{
    public function getTime(): \DateTime;
    public function getName(): string;
    public function getLevel(): string;
    public function getDescription(): ?string;
    public function setDescription(string $description): void;
    public function getIp(): ?string;
    public function setIp(string $ip): void;
    public function getUser(): ?string;
    public function setUser(string $user): void;
    public function getImpersonatingUser(): ?string;
    public function setImpersonatingUser(string $impersonatingUser): void;
    public function getChangeSet(): ?array;
    public function setChangeSet(array $changeSet): void;
}
