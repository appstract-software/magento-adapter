<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductDataStocksInterface;

class ProductDataStocks implements ProductDataStocksInterface
{
  /** @var string $sku */
  private $sku;

  /** @var Appstractsoftware\MagentoAdapter\Api\Data\ProductDataStockSourcesInterface[] $sources */
  private $sources;


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
  public function getSources()
  {
    return $this->sources;
  }
  /**
   * @inheritDoc
   */
  public function setSources($sources)
  {
    $this->sources = $sources;
  }
}
