<?php
namespace Appstractsoftware\MagentoAdapter\Api\ProductImage;

use \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface;

interface ProductImagesSearchInterface
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
     * Get Images
     *
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface[]
     */
    public function getImages();

    /**
     * Set images
     *
     * @return void
     */
    public function setImages($images);

}
