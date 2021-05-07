<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

interface ProductConfigurationsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * Load data for dto.
     *
     * @param Magento\Catalog\Api\Data\ProductInterface $product
     * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductConfigurationsInterface
     */
    public function load(\Magento\Catalog\Api\Data\ProductInterface $product);

    /**
     * @return \Magento\Framework\Api\AttributeInterface[]|null
     */
    public function getAttributes();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getSku();
}
