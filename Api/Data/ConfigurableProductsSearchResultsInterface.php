<?php
namespace Appstractsoftware\MagentoAdapter\Api\Data;

use \Appstractsoftware\MagentoAdapter\Api\Data\ConfigurableProductSearchInterface;

use \Magento\Framework\Api\SearchResultsInterface;

interface ConfigurableProductsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get attributes list.
     *
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\ConfigurableProductSearchInterface[]
     */
    public function getItems();

    /**
     * Set attributes list.
     *
     * @param \Appstractsoftware\MagentoAdapter\Api\Data\ConfigurableProductSearchInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
