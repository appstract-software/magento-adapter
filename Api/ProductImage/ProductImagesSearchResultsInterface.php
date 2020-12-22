<?php
namespace Appstractsoftware\MagentoAdapter\Api\ProductImage;

use \Appstractsoftware\MagentoAdapter\Api\ProductImage\ProductImagesSearchInterface;

use \Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface ProductImagesSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get attributes list.
     *
     * @return \Appstractsoftware\MagentoAdapter\Api\ProductImage\ProductImagesSearchInterface[]
     */
    public function getItems();

    /**
     * Set attributes list.
     *
     * @param \Appstractsoftware\MagentoAdapter\Api\ProductImage\ProductImagesSearchInterface[] $items
     * @return $this
     */
    public function setItems(array $items);

    /**
     * Get search criteria.
     *
     * @return \Magento\Framework\Api\SearchCriteriaInterface
     */
    public function getSearchCriteria();

    /**
     * Set search criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return $this
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount();

    /**
     * Set total count.
     *
     * @param int $totalCount
     * @return $this
     */
    public function setTotalCount($totalCount);
}
