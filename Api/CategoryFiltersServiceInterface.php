<?php

namespace Appstractsoftware\MagentoAdapter\Api;

interface CategoryFiltersServiceInterface
{
    /**
     * Get category filters.
     *
     * @param int $categoryId
     * @return Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersDtoInterface[]
     */
    public function getCategoryFilters($categoryId);
}