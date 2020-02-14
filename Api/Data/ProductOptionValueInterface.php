<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

interface ProductOptionValueInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * Load data for dto.
     *
     * @param Magento\Catalog\Api\Data\ProductInterface $product
     * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValue
     */
    public function load($attribute);

    /**
     * @return int|null
     */
    public function getValueIndex();

    /**
     * @return string|null
     */
    public function getStoreLabel();
}