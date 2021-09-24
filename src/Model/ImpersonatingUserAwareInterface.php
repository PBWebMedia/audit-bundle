<?php declare(strict_types=1);

namespace Pbweb\AuditBundle\Model;

/**
 * @copyright 2016 PB Web Media B.V.
 */
interface ImpersonatingUserAwareInterface
{
    public function getImpersonatingUser(): ?string;
}
