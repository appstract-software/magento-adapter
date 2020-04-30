<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

interface CartItemLinksInterface 
{
    /**
     * Load data for dto.
     *
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface|null
     */
    public function load($product);

    /**
     * Set sku
     *
     * @return void
     */
    public function setSku($sku);

    /**
     * Get sku
     *
     * @return string|null
     */
    public function getSku();

    /**
     * Set url_key
     *
     * @return void
     */
    public function setUrlKey($urlKey);

    /**
     * Get url_key
     *
     * @return string|null
     */
    public function getUrlKey();

    /**
     * Set simple url_key
     *
     * @return void
     */
    public function setSimpleUrlKey($simpleUrlKey);

    /**
     * Get simple url_key
     *
     * @return string|null
     */
    public function getSimpleUrlKey();
}