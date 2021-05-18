<?php

namespace Appstractsoftware\MagentoAdapter\Api;

/**
 * @api
 */
interface PayUOrderInterface
{
    /**
     * Create new PayU order
     *
     * @param string $orderId
     * @param string $continueUrl
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\PayUOrderCreateResponseInterface
     */
    public function createOrder($orderId, $continueUrl);

    /**
     * Get PayU order status
     *
     * @param string $id
     * @return string
     */
    public function getOrderStatus($id);
}
