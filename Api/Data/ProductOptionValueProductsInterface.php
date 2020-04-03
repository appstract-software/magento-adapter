<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

interface ProductOptionValueProductsInterface 
{
    /**
     * Load data for dto.
     *
     * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValueProductsInterface
     */
    public function load($options, $prod);

    /**
     * Get Sku
     *
     * @return string
     */
    public function getSku();

    /**
     * Get Images
     *
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface[]
     */
    public function getImages();

    /**
     * Get Price
     *
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface
     */
    public function getPrice();

    /**
     * Get Attributes
     *
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValueInterface[]
     */
    public function getAttributes();
}