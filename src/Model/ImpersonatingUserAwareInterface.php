<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Model;

interface ImpersonatingUserAwareInterface
{
    public function getImpersonatingUser(): ?string;
}
