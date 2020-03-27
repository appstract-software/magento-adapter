<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

interface ProductPriceInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * Load data for dto.
     *
     * @param Magento\Catalog\Api\Data\ProductInterface $product
     * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface
     */
    public function load($product);
    
    /**
     * @return float|null
     */
    public function getPrice();

    /**
     * @return float|null
     */
    public function getSpecialPrice();

    /**
     * @return string|null
     */
    public function getCurrencyPrice();

    /**
     * @return string|null
     */
    public function getCurrencySpecialPrice();

    /**
     * @return string|null
     */
    public function getCurrencySymbol();
}