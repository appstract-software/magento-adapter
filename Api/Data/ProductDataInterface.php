<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

/**
 * Product interface.
 * @api
 */
interface ProductDataInterface
{
  /**
   * Get Simple Products
   *
   * @return \Magento\Catalog\Model\Product[]
   */
  public function getSimple();

  /**
   * Get Simple Products
   *
   * @return \Magento\Catalog\Model\Product
   */
  public function getConfigurable();

  /**
   * Set Simple Products
   *
   * @return \Magento\Catalog\Model\Product[] $simple
   */
  public function setSimple($simple);

  /**
   * Set Simple Products
   *
   * @return \Magento\Catalog\Model\Product $configurable
   */
  public function setConfigurable($configurable);

  /**
   * Set Simple Products
   *
   * @return string[] $links
   */
  public function getCategoryLinks();

  /**
   * Set Simple Products
   *
   * @return string[] $links
   */
  public function setCategoryLinks($links);

  /**
   * Set Simple Products
   *
   * @return int[] $websiteIds
   */
  public function getWebsiteIds();

  /**
   * Set Simple Products
   *
   * @return int[] $websiteIds
   */
  public function setWebsiteIds($websiteIds);
}
