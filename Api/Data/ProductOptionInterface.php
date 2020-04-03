<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

interface ProductOptionInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * Load data for dto.
     *
     * @param Magento\Catalog\Api\Data\ProductInterface $product
     * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionInterface
     */
    public function load($product, $productAttribute, $options, $data);

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @return string|null
     */
    public function getAttributeId();

    /**
     * @return string|null
     */
    public function getAttributeCode();

    /**
     * @return string|null
     */
    public function getLabel();

    /**
     * @return string|null
     */
    public function getFrontendLabel();
    
    /**
     * @return string|null
     */
    public function getStoreLabel();

    /**
     * @return int|null
     */
    public function getPosition();

    /**
     * @return bool|null
     */
    public function getIsUseDefault();

    /**
     * @return int|null
     */
    public function getProductId();

    /**
     * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValueInterface[]
     */
    public function getValues();
}