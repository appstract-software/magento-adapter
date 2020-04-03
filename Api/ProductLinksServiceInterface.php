<?php

namespace Appstractsoftware\MagentoAdapter\Api;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductLinksInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductLinkInterface;

interface ProductLinksServiceInterface
{
  /**
   * Get product links
   *
   * @param string $sku
   * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductLinksInterface
   */
  public function getLinks($sku);

  /**
   * Get product links
   *
   * @param string $sku
   * @param string $type
   * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductLinkInterface[]
   */
  public function getLinksByType($sku, $type);
}
