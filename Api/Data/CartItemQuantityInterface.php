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
     * Get product id
     *
     * @return int|null
     */
    public function getProductId();

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

    /**
     * Set sku
     *
     * @return void
     */
    public function setSku($sku);

    /**
     * Set id
     *
     * @return void
     */
    public function setProductId($id);

    /**
     * Set quantity
     *
     * @return void
     */
    public function setQty($qty);

    /**
     * Set quantity available
     *
     * @return void
     */
    public function setQtyAvailable($qtyAvailable);
}