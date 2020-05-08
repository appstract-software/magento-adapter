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
     * Get id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set id
     *
     * @return void
     */
    public function setId($id);

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
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface|null
     */
    public function getThumbnail();

    /**
     * Set images
     *
     * @return void
     */
    public function setThumbnail($thumbnail);

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
