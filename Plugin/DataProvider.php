<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use \Appstractsoftware\MagentoAdapter\Api\Data\OrderItemOptionsInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\SalesGraphQl\Model\OrderItem\OptionsProcessor;

/**
 * Data provider for order items
 */
class DataProvider
{
  /**
   * @var OrderItemRepositoryInterface
   */
  private $orderItemRepository;

  /**
   * @var ProductRepositoryInterface
   */
  private $productRepository;

  /**
   * @var OrderRepositoryInterface
   */
  private $orderRepository;

  /**
   * @var SearchCriteriaBuilder
   */
  private $searchCriteriaBuilder;

  /**
   * @var OptionsProcessor
   */
  private $optionsProcessor;

  /**
   * @var int[]
   */
  private $orderItemIds = [];

  /**
   * @var array
   */
  private $orderItemList = [];

  /**
   * @param OrderItemRepositoryInterface $orderItemRepository
   * @param ProductRepositoryInterface $productRepository
   * @param OrderRepositoryInterface $orderRepository
   * @param SearchCriteriaBuilder $searchCriteriaBuilder
   * @param OptionsProcessor $optionsProcessor
   */
  public function __construct(
    OrderItemRepositoryInterface $orderItemRepository,
    ProductRepositoryInterface $productRepository,
    OrderRepositoryInterface $orderRepository,
    SearchCriteriaBuilder $searchCriteriaBuilder,
    OptionsProcessor $optionsProcessor
  ) {
    $this->orderItemRepository = $orderItemRepository;
    $this->productRepository = $productRepository;
    $this->orderRepository = $orderRepository;
    $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    $this->optionsProcessor = $optionsProcessor;
  }


  public function afterGetOrderItemById($subject, $orderItems): array
  {
    var_dump($orderItems);
  }
}
