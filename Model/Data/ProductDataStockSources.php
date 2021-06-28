<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductDataStockSourcesInterface;

class ProductDataStockSources implements ProductDataStockSourcesInterface
{
  /** @var string $source */
  private $source;

  /** @var string $qty */
  private $qty;

  /**
   * @inheritDoc
   */
  public function getSource()
  {
    return $this->source;
  }
  /**
   * @inheritDoc
   */
  public function setSource($source)
  {
    $this->source = $source;
  }

  /**
   * @inheritDoc
   */
  public function getQty()
  {
    return $this->qty;
  }
  /**
   * @inheritDoc
   */
  public function setQty($qty)
  {
    $this->qty = $qty;
  }
}
