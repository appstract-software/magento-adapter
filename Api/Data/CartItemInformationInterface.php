<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

interface CartItemInformationInterface
{
    /**
     * Load data for dto.
     *
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\CartItemInformationInterface|null
     */
    public function load($product, $cartItem);

    /**
     * Set store_code
     *
     * @return void
     */
    public function setStoreCode($store_code);

    /**
     * Get store_code
     *
     * @return string|null
     */
    public function getStoreCode();

    /**
     * Set store_id
     *
     * @return void
     */
    public function setStoreId($store_id);

    /**
     * Get store_id
     *
     * @return string|null
     */
    public function getStoreId();

    /**
     * Set currency_symbol
     *
     * @return void
     */
    public function setCurrencySymbol($currency_symbol);

    /**
     * Get currency_symbol
     *
     * @return string|null
     */
    public function getCurrencySymbol();

    /**
     * Set price_with_currency
     *
     * @return void
     */
    public function setPriceWithCurrency($price_with_currency);

    /**
     * Get price_with_currency
     *
     * @return string|null
     */
    public function getPriceWithCurrency();

    /**
     * Set category_flat_url
     *
     * @return void
     */
    public function setCategoryFlatUrl($category_flat_url);

    /**
     * Get category_flat_url
     *
     * @return string|null
     */
    public function getCategoryFlatUrl();

    /**
     * Set category_tree_url
     *
     * @return void
     */
    public function setCategoryTreeUrl($category_tree_url);

    /**
     * Get category_tree_url
     *
     * @return string|null
     */
    public function getCategoryTreeUrl();
}
