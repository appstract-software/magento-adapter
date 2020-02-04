<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersDtoInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersItemDtoInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersAdditionalInfoPriceDtoInterface;

class CategoryFiltersDto implements CategoryFiltersDtoInterface
{
    /** @var string $fieldName */
    private $fieldName;

    /** @var string $label */
    private $label;

    /** @var int $websiteId */
    private $websiteId;

    /** @var int $storeId */
    private $storeId;

    /** @var int $itemsCount */
    private $itemsCount;

    /** @var Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersAdditionalInfoPriceDtoInterface $additionalInfo */
    private $additionalInfo;

    /** @var Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersItemDtoInterface[] $items */
    private $items;

    /** @var Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersItemDtoInterface item */
    private $itemLoader;

    /** @var Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersAdditionalInfoPriceDtoInterface additionalInfoPrice */
    private $additionalInfoPrice;

    /**
     * Constructor.
     *
     * @param CategoryFiltersItemDtoInterface $itemLoader
     */
    public function __construct(
        CategoryFiltersItemDtoInterface $itemLoader,
        CategoryFiltersAdditionalInfoPriceDtoInterface $additionalInfoPrice
    ) {
        $this->itemLoader = $itemLoader;
        $this->additionalInfoPrice = $additionalInfoPrice;
    }

    /**
     * @inheritDoc
     */
    public function load($filter, $layer)
    {
        $this->fieldName  = $filter->getRequestVar();
        $this->label      = $filter->getName();
        $this->websiteId  = $filter->getWebsiteId();
        $this->storeId    = $filter->getStoreId();
        $this->itemsCount = $filter->getItemsCount();
        
        if ($this->fieldName === 'price') {
            $maxPrice = $layer->getProductCollection()->getMaxPrice();
            $minPrice = $layer->getProductCollection()->getMinPrice();
            $this->additionalInfo = $this->additionalInfoPrice->load($minPrice, $maxPrice);
        } else {
            $this->additionalInfo = [];
        }

        $items = [];
        foreach ($filter->getItems() as $item) {
            $items[] = clone $this->itemLoader->load($item);
        }
        $this->items = $items;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }

    /**
     * @inheritDoc
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @inheritDoc
     */
    public function getWebsiteId(): int
    {
        return $this->websiteId;
    }

    /**
     * @inheritDoc
     */
    public function getStoreId(): int
    {
        return $this->storeId;
    }

    /**
     * @inheritDoc
     */
    public function getItemsCount(): int
    {
        return $this->itemsCount;
    }

    /**
     * @inheritDoc
     */
    public function getItems()
    {
        return $this->items;
    }
}