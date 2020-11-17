<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

interface ProductUnitsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * Load data for dto.
     *
     * @param Magento\Catalog\Api\Data\ProductInterface $product
     * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductUnitsInterface
     */
    public function load($product);

    /**
     * @return string
     */
    public function getDimensionUnit();

    /**
     * @return string
     */
    public function getWeightUnit();
}
