<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use \Appstractsoftware\MagentoAdapter\Api\ProductOptionsServiceInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\ProductsOptionsSearchResultsItemInterface;

use \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use \Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory;
use \Magento\Framework\EntityManager\Operation\Read\ReadExtensions;
use \Magento\Catalog\Model\ResourceModel\Product\Collection;
use \Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use \Magento\Catalog\Api\Data\ProductInterface;
use \Magento\Catalog\Api\ProductRepositoryInterface;

class ProductOptionsService implements ProductOptionsServiceInterface
{
    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface */
    private $productPriceLoader;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionInterface */
    private $productOptionLoader;
    
    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface */
    private $productImagesLoader;
    
    /** @var \Magento\Catalog\Api\ProductRepositoryInterface */
    protected $productRepository;

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

    /** @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProduct */
    private $configurableProduct;

    /** @var \ProductsOptionsSearchResultsItemInterface $productsOptionsSearchResultsItemLoader */
    private $productsOptionsSearchResultsItemLoader;

    /**
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        ProductOptionInterface $productOptionLoader,
        ProductPriceInterface $productPriceLoader,
        ProductImagesInterface $productImagesLoader,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor,
        \Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor = null,
        \Magento\Framework\EntityManager\Operation\Read\ReadExtensions $readExtensions = null,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProduct,
        \Appstractsoftware\MagentoAdapter\Api\Data\ProductsOptionsSearchResultsItemInterface $productsOptionsSearchResultsItemLoader
    ) {
        $this->productRepository = $productRepository;
        $this->productOptionLoader = $productOptionLoader;
        $this->productPriceLoader = $productPriceLoader;
        $this->productImagesLoader = $productImagesLoader;
        $this->collectionFactory = $collectionFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
        $this->readExtensions = $readExtensions ?: \Magento\Framework\App\ObjectManager::getInstance()->get(ReadExtensions::class);
        $this->configurableProduct = $configurableProduct;
        $this->productsOptionsSearchResultsItemLoader = $productsOptionsSearchResultsItemLoader;
    }

    /**
     * @inheritDoc
     */
    public function getCategoryProductsOptions(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->extensionAttributesJoinProcessor->process($collection);

        $collection->addAttributeToSelect('*');
        $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
        $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');

        $this->collectionProcessor->process($searchCriteria, $collection);

        $collection->load();

        $collection->addCategoryIds();
        $this->addExtensionAttributes($collection);

        $items = [];
        foreach ($collection->getItems() as $item) {
            $items[] = clone $this->productsOptionsSearchResultsItemLoader->load(
                $item->getSku(),
                $item->getId(),
                $this->loadData($item)
            );
        }

        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($items);
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * @inheritDoc
     */
    public function getProductOptions($sku)
    {
        $product = $this->productRepository->get($sku);
        return $this->loadData($product);
    }

    /**
     * Load extension attribute data.
     *
     * @param ProductInterface $product
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionInterface[]
     */
    private function loadData($product) {
        $productOptions = [];
        $typeInstance = $product->getTypeInstance(true);
        if (!empty($typeInstance) && method_exists($typeInstance, 'getConfigurableAttributesAsArray')) {
            $data = $product->getTypeInstance()->getConfigurableOptions($product);
            $options = [];
            foreach($data as $attributes) {
                foreach($attributes as $prod){
                    $options[$prod['sku']]['attributes'][$prod['attribute_code']] = [
                        'store_label' => $prod['option_title'],
                        'value_index' => $prod['value_index'],
                    ];
                    $options[$prod['sku']]['images'] = [];
                    $options[$prod['sku']]['price'] = [];
                }
            }

            foreach ($options as $sku => $p) {
                $productSimilar = $this->productRepository->get($sku);
                $options[$sku]['price'] = clone $this->productPriceLoader->load($productSimilar);
                $options[$sku]['images'] = [];
                foreach ($productSimilar->getMediaGalleryImages() as $image) {
                    $options[$sku]['images'][] = clone $this->productImagesLoader->load($image);
                }
            }

            $productAttributeOptions = $typeInstance->getConfigurableAttributesAsArray($product);
            foreach ($productAttributeOptions as $productAttribute) {
                $productOptions[] = clone $this->productOptionLoader->load($product, $productAttribute, $options, $data);
            }
        }

        return $productOptions;
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
