<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

interface CategoryFiltersAdditionalInfoPriceDtoInterface
{
    /**
     * Load data for dto.
     *
     * @return Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersAdditionalInfoPriceDtoInterface
     */
    public function load($min_price, $max_price);

    /**
     * Get min price
     * 
     * @return int
     */
    public function getMinPrice();

    /**
     * Get max price
     * 
     * @return int
     */
    public function getMaxPrice();

    /**
     * Set min price
     * 
     * @return int
     */
    public function setMinPrice($min_price);

    /**
     * Set max price
     * 
     * @return int
     */
    public function setMaxPrice($max_price);
}