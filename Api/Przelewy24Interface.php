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
     * @param int $predefinedMethod
     * @return Appstractsoftware\MagentoAdapter\Api\Data\Przelewy24RegisterTransactionResponseInterface
     */
    public function registerTransaction($orderId, $urlReturn, $predefinedMethod);
}
