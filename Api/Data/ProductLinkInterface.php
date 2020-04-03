<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

use \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface;
use \Magento\Catalog\Api\Data\ProductLinkInterface as ProductLinkInterfaceMagento;
use \Magento\Framework\Data\Collection;

interface ProductLinkInterface 
{
    /**
     * Load data for dto.
     *
     * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductLinkInterface
     */
    public function load($item);

    /**
     * Get Id
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Get Sku
     *
     * @return string
     */
    public function getSku(): string;

    /**
     * Get Type
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Get Name
     *
     * @return string
     */
    public function getName(): string;

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
}