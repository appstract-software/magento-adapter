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

    /** @var string $type */
    private $type;

    /** @var Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersAdditionalInfoPriceDtoInterface $additionalInfo */
    private $additionalInfo;

    /** @var Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersItemDtoInterface[] $items */
    private $items;

    /** @var Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersItemDtoInterface item */
    private $itemLoader;

    /** @var Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersAdditionalInfoPriceDtoInterface additionalInfoPrice */
    private $additionalInfoPrice;

    /** @var Magento\Swatches\Helper\Data swatchHelper */
    private $swatchHelper;

    /** @var \Magento\Eav\Model\Config eavConfig */
    private $eavConfig;

    /**
     * Constructor.
     *
     * @param CategoryFiltersItemDtoInterface $itemLoader
     */
    public function __construct(
        CategoryFiltersItemDtoInterface $itemLoader,
        CategoryFiltersAdditionalInfoPriceDtoInterface $additionalInfoPrice,
        \Magento\Swatches\Helper\Data $swatchHelper,
        \Magento\Eav\Model\Config $eavConfig
    ) {
        $this->itemLoader = $itemLoader;
        $this->additionalInfoPrice = $additionalInfoPrice;
        $this->swatchHelper = $swatchHelper;
        $this->eavConfig = $eavConfig;
    }

    /**
     * @inheritDoc
     */
    public function getSwatchValue($id)
    {
        $hashcodeData = $this->swatchHelper->getSwatchesByOptionsId([$id]);

        return $hashcodeData[$id]['value'];
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
        $attribute = $filter->getData('attribute_model');

        $this->type = !is_null($attribute) ? $attribute->getFrontendInput() : '';

        if ($this->type == 'price') {
            $maxPrice = $layer->getProductCollection()->getMaxPrice();
            $minPrice = $layer->getProductCollection()->getMinPrice();
            $this->additionalInfo = $this->additionalInfoPrice->load($minPrice, $maxPrice);
        } else {
            $this->additionalInfo = [];
        }

        $isSwatch = false;

        if ($this->type == 'select') {
            $eavAttribute = $this->eavConfig->getAttribute('catalog_product', $attribute->getAttributeCode());

            $isVisualSwatch = $this->swatchHelper->isVisualSwatch($eavAttribute);
            $isTextSwatch = $this->swatchHelper->isTextSwatch($eavAttribute);

            if ($isVisualSwatch) {
                $this->type = 'swatch_visual';
                $isSwatch = true;
            }

            if ($isTextSwatch) {
                $this->type = 'swatch_text';
                $isSwatch = true;
            }
        }

        $items = [];
        foreach ($filter->getItems() as $item) {
            if ($isSwatch) {
                $swatchValue = $this->getSwatchValue($item->getValueString());
                $items[] = clone $this->itemLoader->load($item, $swatchValue);
            } else {
                $items[] = clone $this->itemLoader->load($item, '');
            }
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

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return $this->type;
    }
}