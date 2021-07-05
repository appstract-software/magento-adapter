<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use \Appstractsoftware\MagentoAdapter\Api\OrdersManagmentInterface;

class OrdersManagment implements OrdersManagmentInterface
{

  /**
   * @var \Magento\Sales\Api\OrderRepositoryInterface
   */
  public $_orderRepository;

  /**
   * @var \Magento\Framework\Api\SearchCriteriaBuilder
   */
  public $_searchCriteriaBuilder;

  /**
   * @var \Magento\Catalog\Api\ProductRepositoryInterface
   */
  public $_productRepository;

  /**
   * @var \Magento\Framework\Api\FilterBuilder
   */
  public $_filterBuilder;

  public function __construct(
    \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
    \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
    \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
    \Magento\Framework\Api\FilterBuilder $filterBuilder
  ) {
    $this->_orderRepository = $orderRepository;
    $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
    $this->_productRepository = $productRepository;
    $this->_filterBuilder = $filterBuilder;
  }

  /**
   * @param int $customerId
   * @return \Magento\Sales\Api\Data\OrderInterface[]
   */
  public function getListForCustomer($customerId)
  {
    $filters = [
      $this->_filterBuilder->setField('customer_id')->setValue($customerId)->create()
    ];

    $searchCriteria = $this->_searchCriteriaBuilder->addFilters($filters)->create();

    $orders = $this->_orderRepository->getList($searchCriteria)->getItems();

    foreach ($orders as $order) {
      foreach ($order->getItems() as $product) {
        $product->setItemId($this->_productRepository->get($product->getSku())->getId());
      }
    }

    return $orders;
  }
}
