<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

interface CategoryFiltersItemDtoInterface
{
    /**
     * Load data for dto.
     *
     * @return Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersItemDtoInterface
     */
    public function load($item, $swatchValue);

    /**
     * Get field name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Get value
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Get count
     *
     * @return int
     */
    public function getCount(): int;

    /**
     * Get swatch value
     *
     * @return string
     */
    public function getSwatchValue(): string;
}
