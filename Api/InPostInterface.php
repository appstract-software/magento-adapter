<?php

namespace Appstractsoftware\MagentoAdapter\Api;

/**
 * @api
 */
interface InPostInterface
{
    /**
     * @param string $shipmentId
     * @param string $status
     * @return string
     */
    public function updateOrderStatus($shipmentId, $status);
}
