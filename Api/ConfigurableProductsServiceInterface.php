<?php

namespace Appstractsoftware\MagentoAdapter\Api;

use \Magento\Framework\Api\SearchCriteriaInterface;

interface ConfigurableProductsServiceInterface
{
    /**
     * Get configurable product list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function getConfigurableProducts(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}