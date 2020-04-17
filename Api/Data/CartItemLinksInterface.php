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
     * Get sku
     *
     * @return string|null
     */
    public function getSku();

    /**
     * Get url_key
     *
     * @return string|null
     */
    public function getUrlKey();

    /**
     * Get simple url_key
     *
     * @return string|null
     */
    public function getSimpleUrlKey();
}