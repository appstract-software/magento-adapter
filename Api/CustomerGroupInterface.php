<?php

namespace Appstractsoftware\MagentoAdapter\Api;

/**
 * Newsletter interface.
 * @api
 */
interface CustomerGroupInterface
{
    /**
     * Subscribe an email.
     *
     * @param string $cartId
     * @param string $customerGroupId
     * @return \Appstractsoftware\MagentoAdapter\Api\CustomerGroupInterface
     */
    public function setCustomerGroup($cartId, $customerGroupId);
}