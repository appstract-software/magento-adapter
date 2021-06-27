<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

/**
 * Product interface.
 * @api
 */
interface ProductDataStocksInterface
{
  /**
   * Get Stocks
   *
   * @return string $sku
   */
  public function getSku();

  /**
   * Set Sku
   *
   * @return string $sku
   */
  public function setSku($sku);

  /**
   * Get Stocks
   *
   * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductDataStockSourcesInterface[] $sources
   */
  public function getSources();

  /**
   * Set Sources
   *
   * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductDataStockSourcesInterface[] $sources
   */
  public function setSources($sources);
}
