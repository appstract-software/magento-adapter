<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\ConfigurableProductsServiceInterface;

use \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use \Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory;
use \Magento\Framework\EntityManager\Operation\Read\ReadExtensions;
use \Magento\Catalog\Model\ResourceModel\Product\Collection;
use \Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use \Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Configurable products service.
 * 
 * @author Mateusz Lesiak <mateusz.lesiak@appstract.software>
 * @copyright 2020 Appstract Software
 */
class ConfigurableProductsService implements ConfigurableProductsServiceInterface
{
    /** @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory */
    private $collectionFactory;

    /** @var \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor */
    private $extensionAttributesJoinProcessor;

    /** @var \Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory $searchResultsFactory */
    private $searchResultsFactory;

    /** @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor */
    private $collectionProcessor;

    /** @var \Magento\Framework\EntityManager\Operation\Read\ReadExtensions $readExtensions */
    private $readExtensions;

    /** @var \Magento\Framework\EntityManager\Operation\Read\ReadExtensions $configurableProduct */
    private $configurableProduct;

    /** @var \Magento\Framework\EntityManager\Operation\Read\ReadExtensions $productRepository */
    private $productRepository;

    /**
     * Constructor
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor,
        \Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor = null,
        \Magento\Framework\EntityManager\Operation\Read\ReadExtensions $readExtensions = null,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProduct,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
        $this->readExtensions = $readExtensions ?: \Magento\Framework\App\ObjectManager::getInstance()->get(ReadExtensions::class);
        $this->configurableProduct = $configurableProduct;
        $this->productRepository = $productRepository;
    }

    /**
     * @inheritDoc
     */
    public function getConfigurableProducts(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->extensionAttributesJoinProcessor->process($collection);

        $collection->addAttributeToSelect('*');
        $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
        $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');

        $this->resetVisibilityFilter($searchCriteria);

        $oldPageSize = $searchCriteria->getPageSize();
        $searchCriteria->setPageSize(null);
        $oldCurrentPage = $searchCriteria->getCurrentPage() ?: 1;
        $searchCriteria->setCurrentPage(null);
        if (!empty($this->collectionProcessor)) {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }
        $searchCriteria->setPageSize($oldPageSize);
        $searchCriteria->setCurrentPage($oldCurrentPage);

        $collection->load();
        $collection->addCategoryIds();
        $this->addExtensionAttributes($collection);

        $products = $collection->getItems();
        $size = $collection->getSize();

        $items = [];
        $skus = [];
        foreach ($products as $product) {
            if ($product->getTypeId() == "simple") {
                $parentIds = $this->configurableProduct->getParentIdsByChild($product->getId());
                $parentId = array_shift($parentIds);
                $parent = $this->productRepository->getById($parentId);
                if (!in_array($parent->getSku(), $skus)) {
                    $items[] = $parent;
                    $skus[] = $parent->getSku();
                }
            }
        }
        $total = count($items);

        $itemsPage = $items;
        if (!empty($oldPageSize)) {
            $itemsPage = array_slice($items, ($oldCurrentPage - 1) * $oldPageSize, $oldPageSize);
        }

        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($itemsPage);
        $searchResult->setTotalCount($total);

        // TODO: Add cache - look ProductRepositoryInterface cacheProduct method.

        return $searchResult;
    }


    private function resetVisibilityFilter($searchCriteria)
    {
        $filterGroups = $searchCriteria->getFilterGroups();
        try {
            foreach ($filterGroups as &$filterGroup) {
                foreach ($filterGroup->getFilters() as &$filter) {
                    if ($filter->getField() === 'visibility') {
                        $filter->setValue(1);
                        $filter->setConditionType('eq');
                    }
                }
            }
        } catch(\Throwable $th) {}
        $searchCriteria->setFilterGroups($filterGroups);
    }

    /**
     * Retrieve collection processor
     *
     * @return CollectionProcessorInterface
     */
    private function getCollectionProcessor()
    {
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Magento\Catalog\Model\Api\SearchCriteria\ProductCollectionProcessor'
            );
        }
        return $this->collectionProcessor;
    }

    /**
     * Add extension attributes to loaded items.
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    private function addExtensionAttributes(\Magento\Catalog\Model\ResourceModel\Product\Collection $collection)
    {
        if (empty($this->readExtensions)) {
            return $collection;
        }
        foreach ($collection->getItems() as $item) {
            $this->readExtensions->execute($item);
        }
        return $collection;
    }
}