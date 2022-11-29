<?php

declare(strict_types=1);

namespace Appstractsoftware\MagentoAdapter\CustomGQL\Model\Resolver;

use Magento\Framework\Api\SearchCriteria\CollectionProcessor\FilterProcessor\CustomFilterInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Api\Filter;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\InventoryIndexer\Model\StockIndexTableNameResolverInterface;
use Magento\InventoryCatalog\Model\GetStockIdForCurrentWebsite;

class QuantityFilterResolver implements CustomFilterInterface
{
  /**
   * @var CollectionFactory
   */
  private $collectionFactory;

  /**
   * @var StockIndexTableNameResolverInterface
   */
  private $stockIndexTableProvider;

  /**
   * @var GetStockIdForCurrentWebsite
   */
  private $getStockIdForCurrentWebsite;

  public function __construct(
    CollectionFactory $collectionFactory,
    StockIndexTableNameResolverInterface $stockIndexTableProvider,
    GetStockIdForCurrentWebsite $getStockIdForCurrentWebsite
  ) {
    $this->collectionFactory = $collectionFactory;
    $this->stockIndexTableProvider = $stockIndexTableProvider;
    $this->getStockIdForCurrentWebsite = $getStockIdForCurrentWebsite;
  }

  /**
   * @inheritDoc
   */
  public function apply(Filter $filter, AbstractDb $collection)
  {
    // $conditionType = $filter->getConditionType();
    // $attributeValue = $filter->getValue();

    $collection = $this->collectionFactory->create();
    $stockId = $this->getStockIdForCurrentWebsite->execute();
    $inventoryStockTableName = $this->stockIndexTableProvider->execute($stockId);

    $collection->getSelect()
      ->joinLeft(
        ['cpr' => 'catalog_product_relation'],
        'e.entity_id = cpr.parent_id'
      )
      ->joinInner(
        ['cpe' => 'catalog_product_entity'],
        'cpr.child_id = cpe.entity_id'
      )
      ->joinLeft(
        ['is' => $inventoryStockTableName],
        'cpe.sku = is.sku'
      )
      ->joinLeft(
        ['ir' => new \Zend_Db_Expr('(SELECT sku, SUM(IFNULL(quantity, 0)) qty FROM inventory_reservation GROUP BY sku)')],
        'ir.sku = cpe.sku'
      )
      ->group('e.entity_id')
      ->having('availableQty > 0')
      ->reset(\Zend_Db_Select::COLUMNS)
      ->columns(['e.entity_id', 'availableQty' => new \Zend_Db_Expr('SUM(IFNULL(is.quantity, 0) + IFNULL(ir.qty, 0))')]);

    $items = $collection->getItems();
    $productIds = [];

    foreach ($items as $item) {
      $productIds[] = $item['entity_id'];
    }

    // $collection->getSelect()->where($collection->getConnection()->prepareSqlCondition(
    //   'e.entity_id',
    //   ['in' => $entity_ids]
    // ));

    $collection->addIdFilter($productIds);

    return true;
  }
}
