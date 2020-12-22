<?php

namespace Appstractsoftware\MagentoAdapter\Model\ProductImage;

use \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface;
use \Appstractsoftware\MagentoAdapter\Api\ProductImage\ProductImagesServiceInterface;
use \Appstractsoftware\MagentoAdapter\Api\ProductImage\ProductImagesSearchInterface;

use \Magento\Framework\Api\SearchCriteriaInterface;
use \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use \Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory;
use \Magento\Catalog\Model\Product\Gallery\ReadHandler;
use \Magento\Catalog\Model\ResourceModel\Product\Collection;

class ProductImagesService implements ProductImagesServiceInterface
{
  /** @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory */
  private $collectionFactory;

  /** @var \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor */
  private $extensionAttributesJoinProcessor;

  /** @var \Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory $searchResultsFactory */
  private $searchResultsFactory;

  /** @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor */
  private $collectionProcessor;

  /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface */
  private $productImagesLoader;

  /** @var \Appstractsoftware\MagentoAdapter\Api\ProductImage\ProductImagesSearchInterface */
  private $productImagesSearchLoader;

  /**
   * Constructor
   */
  public function __construct(
      \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
      \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor,
      \Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory $searchResultsFactory,
      \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor = null,
      ProductImagesSearch $productImagesSearchLoader
  ) {
      $this->collectionFactory = $collectionFactory;
      $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
      $this->searchResultsFactory = $searchResultsFactory;
      $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
      $this->productImagesSearchLoader = $productImagesSearchLoader;
  }

  /**
   * @inheritDoc
   */
  public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria) {
    /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
    $collection = $this->collectionFactory->create();

    $searchCriteria->setPageSize($searchCriteria->getPageSize() ?: 3);
    $searchCriteria->setCurrentPage($searchCriteria->getCurrentPage() ?: 1);

    if (!empty($this->collectionProcessor)) {
        $this->collectionProcessor->process($searchCriteria, $collection);
    }

    $collection->load();
    $collectionItems = $collection->getItems();
    $items = [];
    foreach ($collectionItems as $product) {
      $items[] = clone $this->productImagesSearchLoader->load($product);
    }

    $searchResult = $this->searchResultsFactory->create();
    $searchResult->setSearchCriteria($searchCriteria);
    $searchResult->setItems($items);
    $searchResult->setTotalCount($collection->getSize());

    return $searchResult;
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
}
