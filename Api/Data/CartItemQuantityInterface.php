<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

interface CartItemQuantityInterface 
{
    /**
     * Load data for dto.
     *
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\CartItemQuantityInterface|null
     */
    public function load($cartItem, $product);

    /**
     * Get sku
     *
     * @return string|null
     */
    public function getSku();

    /**
     * Get quantity
     *
     * @return int|null
     */
    public function getQty();

    /**
     * Get quantity available
     *
     * @return int|null
     */
    public function getQtyAvailable();
}