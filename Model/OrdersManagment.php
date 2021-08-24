<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use \Appstractsoftware\MagentoAdapter\Api\OrdersManagmentInterface;
use Magento\Framework\Api\Search\SearchCriteriaInterface;

class OrdersManagment implements OrdersManagmentInterface
{
  /**
   * @var \Magento\Store\Model\StoreManagerInterface
   */
  private $storeManager;

  /**
   * @var \Magento\Catalog\Api\ProductRepositoryInterface $apiProductRepository,
   */
  public $_productRepository;

  /**
   * @var \Magento\Sales\Api\OrderRepositoryInterface
   */
  public $_orderRepository;

  /**
   * @var \Magento\Framework\Api\SearchCriteriaBuilder
   */
  public $_searchCriteriaBuilder;

  /**
   * @var \Magento\Framework\Api\FilterBuilder
   */
  public $_filterBuilder;

  /**
   * @var \Magento\Sales\Api\Data\OrderItemExtensionFactory
   */
  public $orderItemExtensionFactory;

  /**
   * @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable
   */
  public $configurableProduct;

  /**
   * @var \Appstractsoftware\MagentoAdapter\Api\Data\OrderItemOptionsInterface
   */
  public $orderItemOptions;

  /**
   * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
   */
  public $productCollectionFactory;

  public function __construct(
    \Magento\Sales\Api\Data\OrderItemExtensionFactory $orderItemExtensionFactory,
    \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProduct,
    \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
    \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
    \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Framework\Api\FilterBuilder $filterBuilder,
    \Appstractsoftware\MagentoAdapter\Api\Data\OrderItemOptionsInterface $orderItemOptions,
    \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
  ) {
    $this->_productCollectionFactory = $productCollectionFactory;
    $this->orderItemExtensionFactory = $orderItemExtensionFactory;
    $this->configurableProduct = $configurableProduct;
    $this->_productRepository = $productRepository;
    $this->_orderRepository = $orderRepository;
    $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
    $this->storeManager = $storeManager;
    $this->_filterBuilder = $filterBuilder;
    $this->orderItemOptions = $orderItemOptions;
  }

  /**
   * @param int $customerId
   * @param SearchCriteriaInterface $searchCriteria
   * @return \Magento\Sales\Api\Data\OrderInterface[]
   */
  public function getListForCustomer($customerId, $searchCriteria)
  {
    $filters = [
      $this->_filterBuilder->setField('customer_id')->setValue($customerId)->create()
    ];

    $customerSearchCriteria = $this->_searchCriteriaBuilder
      ->addFilters($filters)
      ->setSortOrders($searchCriteria->getSortOrders())
      ->create()
      ->setCurrentPage($searchCriteria->getCurrentPage())
      ->setPageSize($searchCriteria->getPageSize());

    $items = $this->_orderRepository->getList($customerSearchCriteria)->getItems();

    $products = $this->getProducts($items);
    $parents = $this->getParents($products);
    $filteredProducts = [];

    foreach ($items as $order) {
      $filteredOrderItems = [];
      foreach ($order->getItems() as $orderItem) {
        $productType = $orderItem->getProductType();

        if (
          $productType == \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE ||
          $productType == \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL
        ) {
          $filteredOrderItems[] = $orderItem;
        }
      }
      $order->setItems($filteredOrderItems);
    }

    foreach ($items as $order) {
      foreach ($order->getItems() as $orderItem) {
        foreach ($filteredProducts as $product) {
          if ($orderItem->getSku() === $product->getSku()) {
            $this->assignExtensionAttributes($orderItem, $product, $parents);
          }
        }
      }
    }

    return $items;
  }

  public function getProducts($items)
  {
    $skus = [];

    foreach ($items as $item) {
      foreach ($item->getItems() as $product) {
        if (!in_array($product->getSku(), $skus)) {
          $skus[] = $product->getSku();
        }
      }
    }

    $collection = $this->_productCollectionFactory->create();
    $collection->addAttributeToSelect('*');
    $collection->addFieldToFilter('sku', ['in' => $skus]);
    return $collection;
  }

  public function getParents($products)
  {
    $productParentIds = [];

    foreach ($products as $product) {
      $parentIds = $this->configurableProduct->getParentIdsByChild($product->getId());

      if (!empty($parentIds)) {
        $parentId = array_shift($parentIds);
        if (!in_array($parentId, $productParentIds)) {
          $productParentIds[] = $parentId;
        }
      }
    }



    if (empty($productParentIds)) {
      return [];
    }

    $collection = $this->_productCollectionFactory->create();
    $collection->addAttributeToSelect('*');
    $collection->addFieldToFilter('entity_id', ['in' => $productParentIds]);
    return $collection;
  }


  public function assignExtensionAttributes(
    $orderItem,
    $product,
    $parents
  ) {
    $imageUrl = $this->getProductImage($product);

    $orderItemExtension = $this->orderItemExtensionFactory->create();
    $orderItemExtension->setProductImage($imageUrl);

    if (!empty($parents)) {
      $options = $this->getProductOptions($orderItem, $product, $parents);
      $orderItemExtension->setOptions($options);
    }


    $orderItem->setExtensionAttributes($orderItemExtension);
  }

  private function getProductOptions($orderItem, $product, $parents)
  {
    $storeId = $orderItem->getStoreId();
    $productOptions = [];

    if (!$storeId) {
      return null;
    }

    try {
      $productType = $product->getTypeId();
      if ($productType == \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE || $productType == \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL) {
        $parentIds = $this->configurableProduct->getParentIdsByChild($product->getId());

        if (!empty($parentIds)) {
          $parentId = array_shift($parentIds);
          $parent = $this->fintParentById($parents, $parentId);
          $data = $parent->getTypeInstance()->getConfigurableOptions($parent);

          foreach ($data as $attributes) {
            foreach ($attributes as $prod) {
              if ($product->getSku() == $prod['sku']) {
                $productOptions[] = clone $this->orderItemOptions->load($prod['attribute_code'], $prod['option_title']);
              }
            }
          }
        }
      }
    } catch (\Magento\Framework\Exception\NoSuchEntityException $ex) {
    }

    return empty($productOptions) ? null : $productOptions;
  }


  /**
   * Add cart options to extension attributes.
   * 
   * @return string
   */
  private function getProductImage($product)
  {
    try {
      $imageUrl = $product->getImage();

      if (!$imageUrl) {
        return null;
      }

      return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
        . 'catalog/product' . $imageUrl;
    } catch (\Magento\Framework\Exception\NoSuchEntityException $ex) {
    }

    return null;
  }

  private function fintParentById($parentIds, $id)
  {
    foreach ($parentIds as $parent) {
      if ($id == $parent->getId()) {
        return $parent;
      }
    }

    return null;
  }
}
