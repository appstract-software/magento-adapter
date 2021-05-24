<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductsSearchResultsInterface;

class ProductsSearchResults implements ProductsSearchResultsInterface
{
    /**
     * @inheritDoc
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * @inheritDoc
     */
    public function getSearchCriteria()
    {
        return $this->searchCriteria;
    }

    /**
     * @inheritDoc
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $this->searchCriteria = $searchCriteria;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * @inheritDoc
     */
    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;
        return $this;
    }
}