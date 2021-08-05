<?php

namespace Appstractsoftware\MagentoAdapter\Api;

use Magento\Inventory\Model\SourceItem\Command\SourceItemsSave;

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
