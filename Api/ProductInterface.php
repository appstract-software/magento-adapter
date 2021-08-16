<?php

namespace Appstractsoftware\MagentoAdapter\Api;

/**
 * Product interface.
 * @api
 */
interface ProductInterface
{
  /**
   * Create Products
   *
   * @param \Appstractsoftware\MagentoAdapter\Api\Data\ProductDataInterface $products
   * @return \Appstractsoftware\MagentoAdapter\Api\ProductInterface
   */
  public function create($products);

  /**
   * Create Products
   *
   * @param string $sku
   * @param \Magento\Framework\Api\AttributeInterface[] $attributes
   * @return boolean
   */
  public function setAttributes($sku, $attributes);
}
