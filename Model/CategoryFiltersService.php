<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\CategoryFiltersServiceInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersDtoInterface;

use Magento\Framework\App\ObjectManager;
use Magento\Catalog\Model\Layer\Category\FilterableAttributeList;

/**
 * CategoryFilters.
 * 
 * @author Mateusz Lesiak <mateusz.lesiak@appstract.software>
 * @copyright 2020 Appstract Software
 */
class CategoryFiltersService implements CategoryFiltersServiceInterface
{
    /** @var \Magento\Catalog\Model\Layer\Resolver $resolver */
    private $resolver;

    /** @var \Magento\Catalog\Model\Layer\FilterList $filterList */
    private $filterList;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersDtoInterface $categoryFiltersDto */
    private $categoryFiltersDto;

    /**
     * Constructor
     *
     * @param \Magento\Catalog\Model\Layer\FilterList $filterList
     * @param \Magento\Catalog\Model\Layer\Resolver $resolver
     */
    public function __construct(
        CategoryFiltersDtoInterface $categoryFiltersDto,
        \Magento\Catalog\Model\Layer\FilterListFactory $filterFactory,
        \Magento\Catalog\Model\Layer\Resolver $resolver
    ) {
        $this->categoryFiltersDto = $categoryFiltersDto;
        $this->resolver = $resolver;
        $objectManager = ObjectManager::getInstance();
        $filterableAttributes = $objectManager->getInstance()->get(FilterableAttributeList::class);
        $this->filterList = $filterFactory->create(
            [ 'filterableAttributes' => $filterableAttributes ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getCategoryFilters($categoryId)
    {
        $layer = $this->resolver->get();
        if ($categoryId != 0) {
            $layer->setCurrentCategory($categoryId);
        }        
        $filters = $this->filterList->getFilters($layer);

        $categoryFilters = [];
        foreach ($filters as $filter) {
            if ($filter->getItemsCount() > 0) {
                $categoryFilters[] = clone $this->categoryFiltersDto->load($filter);
            }
        }
        return $categoryFilters;
    }
}