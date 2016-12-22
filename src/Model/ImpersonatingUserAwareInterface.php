<?php

namespace Pbweb\AuditBundle\Model;

/**
 * Interface ImpersonatingUserAwareInterface
 *
 * @copyright 2016 PB Web Media B.V.
 */
interface ImpersonatingUserAwareInterface
{
    /**
     * @return string|null
     */
    public function getImpersonatingUser();
}
