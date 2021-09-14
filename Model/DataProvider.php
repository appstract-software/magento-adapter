<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\SalesGraphQl\Model\Orderitem\OptionsProcessor;
use Magento\Catalog\Model\Product\Type;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

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
        OptionsProcessor $optionsProcessor,
        Configurable $configurableProduct
    ) {
        $this->orderItemRepository = $orderItemRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->optionsProcessor = $optionsProcessor;
        $this->configurableProduct = $configurableProduct;
    }

    /**
     * Add order item id to list for fetching
     *
     * @param int $orderItemId
     */
    public function addOrderItemId(int $orderItemId): void
    {
        if (!in_array($orderItemId, $this->orderItemIds)) {
            $this->orderItemList = [];
            $this->orderItemIds[] = $orderItemId;
        }
    }

    /**
     * Get order item by item id
     *
     * @param int $orderItemId
     * @param boolean $withParent
     * @return array
     */
    public function getOrderItemById(int $orderItemId, $withParent): array
    {
        $orderItems = $this->fetch($withParent);
        if (!isset($orderItems[$orderItemId])) {
            return [];
        }
        return $orderItems[$orderItemId];
    }

    /**
     * Fetch order items and return in format for GraphQl
     * @param boolean $withParent
     * @return array
     */
    private function fetch($withParent)
    {
        if (empty($this->orderItemIds) || !empty($this->orderItemList)) {
            return $this->orderItemList;
        }

        $itemSearchCriteria = $this->searchCriteriaBuilder
            ->addFilter(OrderItemInterface::ITEM_ID, $this->orderItemIds, 'in')
            ->create();

        $orderItems = $this->orderItemRepository->getList($itemSearchCriteria)->getItems();
        $productData = $this->fetchProducts($orderItems, $withParent);
        $productList = $productData['productList'];
        $parentList = $productData['parentList'];
        $orderList = $this->fetchOrders($orderItems);

        foreach ($orderItems as $orderItem) {
            /** @var ProductInterface $associatedProduct */
            $associatedProduct = $productList[$orderItem->getProductId()] ?? null;
            /** @var OrderInterface $associatedOrder */
            $associatedOrder = $orderList[$orderItem->getOrderId()];
            $itemOptions = $this->optionsProcessor->getItemOptions($orderItem);

            $this->orderItemList[$orderItem->getItemId()] = [
                'id' => base64_encode($orderItem->getItemId()),
                'associatedProduct' => $associatedProduct,
                'model' => $orderItem,
                'product_name' => $orderItem->getName(),
                'product_sku' => $orderItem->getSku(),
                'product_url_key' => $associatedProduct ? $associatedProduct->getUrlKey() : null,
                'product_type' => $orderItem->getProductType(),
                'status' => $orderItem->getStatus(),
                'discounts' => $this->getDiscountDetails($associatedOrder, $orderItem),
                'product_sale_price' => [
                    'value' => $orderItem->getPrice(),
                    'currency' => $associatedOrder->getOrderCurrencyCode()
                ],
                'selected_options' => $itemOptions['selected_options'],
                'entered_options' => $itemOptions['entered_options'],
                'quantity_ordered' => $orderItem->getQtyOrdered(),
                'quantity_shipped' => $orderItem->getQtyShipped(),
                'quantity_refunded' => $orderItem->getQtyRefunded(),
                'quantity_invoiced' => $orderItem->getQtyInvoiced(),
                'quantity_canceled' => $orderItem->getQtyCanceled(),
                'quantity_returned' => $orderItem->getQtyReturned(),
                'parent' => array_key_exists($orderItem->getProductId(), $parentList) ? $parentList[$orderItem->getProductId()] : null,
            ];
        }

        return $this->orderItemList;
    }

    /**
     * Fetch associated products for order items
     *
     * @param array $orderItems
     * @return array
     */
    private function fetchProducts(array $orderItems, $withParent): array
    {
        $productIds = array_map(
            function ($orderItem) {
                return $orderItem->getProductId();
            },
            $orderItems
        );

        $parentList = [];

        if ($withParent) {
            $ids = [];
            $parentsData = [];

            foreach ($orderItems as $item) {
                $productType = $item->getProductType();
                $productId = $item->getProductId();

                if ($productType === Type::TYPE_SIMPLE || $productType === Type::TYPE_VIRTUAL) {
                    $parentIds = $this->configurableProduct->getParentIdsByChild($productId);

                    if (empty($parentIds)) {
                        continue;
                    }

                    $parentId = array_shift($parentIds);
                    $ids[] = $parentId;
                    $parentsData[$productId] = $parentId;
                }
            }

            $parentSearchCriteria = $this->searchCriteriaBuilder->addFilter('entity_id', $ids, 'in')->create();
            $parents = $this->productRepository->getList($parentSearchCriteria)->getItems();

            foreach ($parentsData as $productId => $parentId) {
                foreach ($parents as $parent) {
                    if ($parent->getId() == $parentId) {
                        $parentList[$productId] = $parent;
                        break;
                    }
                }
            }
        }

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('entity_id', $productIds, 'in')
            ->create();
        $products = $this->productRepository->getList($searchCriteria)->getItems();
        $productList = [];
        foreach ($products as $product) {
            $productList[$product->getId()] = $product;
        }

        return array(
            'productList' => $productList,
            'parentList' => $parentList,
        );
    }

    /**
     * Fetch associated order for order items
     *
     * @param array $orderItems
     * @return array
     */
    private function fetchOrders(array $orderItems): array
    {
        $orderIds = array_map(
            function ($orderItem) {
                return $orderItem->getOrderId();
            },
            $orderItems
        );

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('entity_id', $orderIds, 'in')
            ->create();
        $orders = $this->orderRepository->getList($searchCriteria)->getItems();

        $orderList = [];
        foreach ($orders as $order) {
            $orderList[$order->getEntityId()] = $order;
        }
        return $orderList;
    }

    /**
     * Returns information about an applied discount
     *
     * @param OrderInterface $associatedOrder
     * @param OrderItemInterface $orderItem
     * @return array
     */
    private function getDiscountDetails(OrderInterface $associatedOrder, OrderItemInterface $orderItem): array
    {
        if (
            $associatedOrder->getDiscountDescription() === null && $orderItem->getDiscountAmount() == 0
            && $associatedOrder->getDiscountAmount() == 0
        ) {
            $discounts = [];
        } else {
            $discounts[] = [
                'label' => $associatedOrder->getDiscountDescription() ?? __('Discount'),
                'amount' => [
                    'value' => abs($orderItem->getDiscountAmount()) ?? 0,
                    'currency' => $associatedOrder->getOrderCurrencyCode()
                ]
            ];
        }
        return $discounts;
    }
}
