<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ConfigurableProductsSearchResultsInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ConfigurableProductSearchInterface;

class ConfigurableProductsSearchResults implements ConfigurableProductsSearchResultsInterface
{
    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ConfigurableProductSearchInterface[] $items */
    private $items = [];

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