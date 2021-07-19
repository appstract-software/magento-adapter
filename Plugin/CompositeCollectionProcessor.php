<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\GraphQl\Model\Query\ContextInterface;
use Magento\InventoryCatalog\Model\GetStockIdForCurrentWebsite;
use Magento\InventoryCatalogApi\Api\DefaultStockProviderInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\InventoryIndexer\Model\StockIndexTableNameResolverInterface;
use Magento\InventoryIndexer\Indexer\IndexStructure;

class CompositeCollectionProcessor
{
  /**
   * @var GetStockIdForCurrentWebsite
   */
  private $getStockIdForCurrentWebsite;

  /**
   * @var StockIndexTableNameResolverInterface
   */
  private $stockIndexTableProvider;

  /**
   * @param GetStockIdForCurrentWebsite $getStockIdForCurrentWebsite
   * @param DefaultStockProviderInterface $defaultStockProvider
   */
  public function __construct(
    GetStockIdForCurrentWebsite $getStockIdForCurrentWebsite,
    DefaultStockProviderInterface $defaultStockProvider,
    StockIndexTableNameResolverInterface $stockIndexTableProvider
  ) {
    $this->getStockIdForCurrentWebsite = $getStockIdForCurrentWebsite;
    $this->defaultStockProvider = $defaultStockProvider;
    $this->stockIndexTableProvider = $stockIndexTableProvider;
  }

  public function afterProcess(
    $subject,
    Collection $result,
    Collection $collection,
    SearchCriteriaInterface $searchCriteria,
    array $attributeNames,
    ContextInterface $context = null
  ) {
    $stockId = $this->getStockIdForCurrentWebsite->execute();

    if ($this->defaultStockProvider->getId() === $stockId) {
      return $result;
    }

    $hasSortByIsSalable = false;
    $direction = \Magento\Framework\Api\SortOrder::SORT_ASC;

    foreach ($searchCriteria->getSortOrders() as $sort) {
      if ($sort->getField() === 'is_salable') {
        $hasSortByIsSalable = true;
        $direction = $sort->getDirection();
        break;
      }
    }

    if (!$hasSortByIsSalable) {
      return $result;
    }

    $tableName = $this->stockIndexTableProvider->execute($stockId);

    $result->getSelect()->join(
      ['msi_stock_status_index' => $tableName],
      'e.sku = msi_stock_status_index.sku',
      []
    )->order('msi_stock_status_index.' . IndexStructure::IS_SALABLE . ' ' . $direction);

    // $result->getSelect()->joinLeft(
    //   ['soi' => 'sales_order_item'],
    //   'e.entity_id = soi.product_id',
    //   array('qty_ordered' => '(select sum(soi.qty_ordered) from sales_order_item soi where soi.product_id = e.entity_id)')
    // )->distinct(true)
    //   ->order('qty_ordered ' . $direction);

    return $result;
  }
}
