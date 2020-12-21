<?php

namespace Appstractsoftware\MagentoAdapter\Api\ProductImage;

use \Magento\Framework\Api\SearchCriteriaInterface;
use \Magento\Framework\Api\SearchResultsInterface;

interface ProductImagesServiceInterface
{
  /**
   * Get product images
   *
   * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
   * @return \Appstractsoftware\MagentoAdapter\Api\ProductImage\ProductImagesSearchResultsInterface
   */
  public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
