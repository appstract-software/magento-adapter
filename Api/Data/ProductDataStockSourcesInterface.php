<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

/**
 * Product interface.
 * @api
 */
interface ProductDataStockSourcesInterface
{
  /**
   * Get Stocks
   *
   * @return string $source
   */
  public function getSource();

  /**
   * Set Source
   *
   * @return string $source
   */
  public function setSource($source);

  /**
   * Get Stocks
   *
   * @return int $qty
   */
  public function getQty();

  /**
   * Set Qty
   *
   * @return int $qty
   */
  public function setQty($qty);
}
