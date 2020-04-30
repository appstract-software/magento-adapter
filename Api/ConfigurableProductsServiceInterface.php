<?php

namespace Appstractsoftware\MagentoAdapter\Api;

use \Appstractsoftware\MagentoAdapter\Api\Data\ConfigurableProductsSearchResultsInterface;

use \Magento\Framework\Api\SearchCriteriaInterface;

interface ConfigurableProductsServiceInterface
{
    /**
     * Get configurable product list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\ConfigurableProductsSearchResultsInterface
     */
    public function getConfigurableProducts(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}