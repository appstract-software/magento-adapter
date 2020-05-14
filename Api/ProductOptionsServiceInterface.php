<?php

namespace Appstractsoftware\MagentoAdapter\Api;

use \Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionInterface;

use \Magento\Framework\Api\SearchCriteriaInterface;
use \Magento\Catalog\Api\Data\ProductSearchResultsInterface;

interface ProductOptionsServiceInterface
{
  /**
   * Get product options
   *
   * @param string $sku
   * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionInterface[]
   */
  public function getProductOptions($sku);

  /**
   * Get category products options
   *
   * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
   * @return \Appstractsoftware\MagentoAdapter\Api\Data\ProductsOptionsSearchResultsInterface
   */
  public function getCategoryProductsOptions(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
