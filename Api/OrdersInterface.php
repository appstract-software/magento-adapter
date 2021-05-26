<?php

namespace Appstractsoftware\MagentoAdapter\Api;

/**
 * @api
 */
interface OrdersInterface
{
    /**
     * Set order status
     *
     * @param string $orderId
     * @param string $status
     * @return string
     */
    public function setStatus($orderId, $status);
}
