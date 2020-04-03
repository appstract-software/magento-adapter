<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

use \Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValueProductsInterface;

interface ProductOptionValueInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * Load data for dto.
     *
     * @param mixed $attribute
     * @param string $attribute_code
     * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValue
     */
    public function load($attribute, $attribute_code, $options, $data);

    /**
     * @return int|null
     */
    public function getValueIndex();

    /**
     * @return string|null
     */
    public function getStoreLabel();

    /**
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValueProductsInterface[]|null
     */
    public function getProducts();
}