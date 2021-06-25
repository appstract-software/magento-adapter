<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductDataInterface;

class ProductData implements ProductDataInterface
{
  /** @var \Magento\Catalog\Model\Product[] $simple */
  private $simple;

  /** @var \Magento\Catalog\Model\Product $configurable */
  private $configurable;

  /** @var string[] $links */
  private $links;

  /** @var int[] $websiteIds */
  private $websiteIds;

  /**
   * @inheritDoc
   */
  public function getSimple()
  {
    return $this->simple;
  }

  /**
   * @inheritDoc
   */
  public function getConfigurable()
  {
    return $this->configurable;
  }

  /**
   * @inheritDoc
   */
  public function getCategoryLinks()
  {
    return $this->links;
  }

  /**
   * @inheritDoc
   */
  public function getWebsiteIds()
  {
    return $this->websiteIds;
  }

  /**
   * @inheritDoc
   */
  public function setSimple($simple)
  {
    $this->simple = $simple;
  }

  /**
   * @inheritDoc
   */
  public function setConfigurable($configurable)
  {
    $this->configurable = $configurable;
  }

  /**
   * @inheritDoc
   */
  public function setCategoryLinks($links)
  {
    $this->links = $links;
  }

  /**
   * @inheritDoc
   */
  public function setWebsiteIds($websiteIds)
  {
    $this->websiteIds = $websiteIds;
  }
}
