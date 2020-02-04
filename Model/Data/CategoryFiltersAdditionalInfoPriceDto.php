<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersAdditionalInfoPriceDtoInterface;

class CategoryFiltersAdditionalInfoPriceDto implements CategoryFiltersAdditionalInfoPriceDtoInterface
{
    /** @var int $minPrice */
    private $minPrice;
 
    /** @var int $maxPrice */
    private $maxPrice;

    /**
     * @inheritDoc
     */
    public function load($min_price, $max_price)
    {
        $this->minPrice  = $min_price;
        $this->maxPrice  = $max_price;

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
    public function getMinPrice()
    {
        return $this->minPrice;
    }


    /**
     * @inheritDoc
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }
}