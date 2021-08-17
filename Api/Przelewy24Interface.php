<?php

namespace Appstractsoftware\MagentoAdapter\Api;

/**
 * @api
 */
interface Przelewy24Interface
{
    /**
     * Register new Przelewy24 transaction
     *
     * @param string $orderId
     * @param string $urlReturn
     * @return string
     */
    public function registerTransaction($orderId, $urlReturn);
}
