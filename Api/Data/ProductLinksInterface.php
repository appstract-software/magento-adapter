<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductLinkInterface;

use Magento\Catalog\Api\Data\ProductInterface;

interface ProductLinksInterface 
{
    /**
     * Load data for dto.
     *
     * @return Magento\Catalog\Api\Data\ProductInterface $product
     */
    public function load($product);

    /**
     * Get related products
     *
     * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductLinkInterface[]
     */
    public function getRelated();

    /**
     * Get cross sell products.
     *
     * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductLinkInterface[]
     */
    public function getCrosssell();

    /**
     * Get up sell products
     *
     * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductLinkInterface[]
     */
    public function getUpsell();
}