<?php

namespace Appstractsoftware\MagentoAdapter\Api;

interface ProductsSearchServiceInterface
{
    /**
     * Search products
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\ProductsSearchResultsInterface
     */
    public function searchProducts(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Search products by query
     *
     * @param \Magento\Framework\Api\Search\SearchCriteriaInterface $searchCriteria
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\ProductsSearchResultsInterface
     */
    public function searchProductsByQuery(\Magento\Framework\Api\Search\SearchCriteriaInterface $searchCriteria);
}