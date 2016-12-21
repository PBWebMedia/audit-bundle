<?php

namespace Pbweb\AuditBundle\Event;

/**
 * Interface AuditEventInterface
 *
 * @copyright 2016 PB Web Media B.V.
 */
interface AuditEventInterface
{
    public function getName(): string;

    public function getLevel(): string;

    /**
     * @return string|null
     */
    public function getDescription();

    public function setDescription(string $description): AuditEventInterface;

    /**
     * @return string|null
     */
    public function getIp();

    public function setIp(string $ip): AuditEventInterface;

    /**
     * @return string|null
     */
    public function getUser();

    public function setUser(string $user): AuditEventInterface;

    /**
     * @return array|null
     */
    public function getImpersonatingUser();

    public function setImpersonatingUser(string $impersonatingUser): AuditEventInterface;

    /**
     * @return array|null
     */
    public function getChangeSet();

    public function setChangeSet(array $changeSet): AuditEventInterface;
}
