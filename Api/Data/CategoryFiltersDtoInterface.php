<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

interface CategoryFiltersDtoInterface
{
    /**
     * Load data for dto.
     *
     * @return Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersDtoInterface
     */
    public function load($item, $layer);

    /**
     * Get additional info.
     *
     * @return Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersAdditionalInfoPriceDtoInterface
     */
    public function getAdditionalInfo();

    /**
     * Get field name.
     *
     * @return string
     */
    public function getFieldName(): string;

    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Get website id
     *
     * @return int
     */
    public function getWebsiteId(): int;

    /**
     * Get store id
     *
     * @return int
     */
    public function getStoreId(): int;

    /**
     * Get items count
     *
     * @return int
     */
    public function getItemsCount(): int;

    /**
     * Get items
     * 
     * @return Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersItemDtoInterface[]
     */
    public function getItems();
}