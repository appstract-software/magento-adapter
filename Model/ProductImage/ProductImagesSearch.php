<?php

namespace Appstractsoftware\MagentoAdapter\Model\ProductImage;

use Appstractsoftware\MagentoAdapter\Api\ProductImage\ProductImagesSearchInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface;

use \Magento\Catalog\Helper\Image;
use \Magento\Catalog\Model\Product\Gallery\ReadHandler;

class ProductImagesSearch implements ProductImagesSearchInterface
{
  /** @var string $sku */
  private $sku;

  /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface[] $images */
  private $images;

  /** @var \Magento\Catalog\Model\Product\Gallery\ReadHandler $readHandler */
  private $readHandler;

  /**
   * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
   */
  public function __construct(
    \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface $productImagesLoader,
    \Magento\Catalog\Model\Product\Gallery\ReadHandler $readHandler
  ) {
      $this->productImagesLoader = $productImagesLoader;
      $this->readHandler = $readHandler;
  }

  /**
   * @inheritDoc
   */
  public function load($product)
  {
      $this->sku      = $product->getSku();
      $this->images = [];

      $this->readHandler->execute($product);
      $images = $product->getMediaGalleryImages();
      foreach ($images as $image) {
        $parsed = clone $this->productImagesLoader->load($image);
        $this->images[] = $parsed;
      }

      return $this;
  }

  /**
   * @inheritDoc
   */
  public function getSku()
  {
      return $this->sku;
  }

  /**
   * @inheritDoc
   */
  public function setSku($sku)
  {
      $this->sku = $sku;
  }

  /**
   * @inheritDoc
   */
  public function getImages()
  {
      return $this->images;
  }

  /**
   * @inheritDoc
   */
  public function setImages($images)
  {
      $this->images = $images;
  }
}
