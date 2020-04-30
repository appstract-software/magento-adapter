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
}