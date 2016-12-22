<?php

namespace Pbweb\AuditBundle\Entity;

/**
 * Interface ImpersonatingUserAwareEntityInterface
 *
 * @copyright 2016 PB Web Media B.V.
 */
interface ImpersonatingUserAwareEntityInterface
{
    /**
     * @return string|null
     */
    public function getImpersonatingUser();
}
