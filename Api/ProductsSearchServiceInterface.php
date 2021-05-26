<?php

namespace Appstractsoftware\MagentoAdapter\Api;

interface ProductsSearchServiceInterface
{
    /**
     * Get products list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\ProductsSearchResultsInterface
     */
    public function searchProducts(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}