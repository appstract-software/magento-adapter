<?php
namespace Appstractsoftware\MagentoAdapter\Api\Data;

use \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface;

interface ConfigurableProductSearchInterface
{
    /**
     * Load
     *
     * @return $this
     */
    public function load($product);

    /**
     * Get sku
     *
     * @return string|null
     */
    public function getSku();

    /**
     * Set sku
     *
     * @return void
     */
    public function setSku($sku);

    /**
     * Get sku
     *
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     *
     * @return void
     */
    public function setName($name);

    /**
     * Get Images
     *
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface[]|null
     */
    public function getImages();

    /**
     * Set images
     *
     * @return void
     */
    public function setImages($images);

    /**
     * Get Price
     *
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface|null
     */
    public function getPrice();

    /**
     * Set price
     *
     * @return void
     */
    public function setPrice($price);

    /**
     * Get links
     *
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface|null
     */
    public function getLinks();

    /**
     * Set links
     *
     * @return void
     */
    public function setLinks($links);
}
