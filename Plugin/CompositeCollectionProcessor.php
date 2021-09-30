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

    if ($this->defaultStockProvider->getId() === $stockId || is_null($searchCriteria->getSortOrders())) {
      return $result;
    }

    $hasSortByIsSalable = false;

    foreach ($searchCriteria->getSortOrders() as $sort) {
      if ($sort->getField() === 'is_salable') {
        $hasSortByIsSalable = true;
        break;
      }
    }

    if (!$hasSortByIsSalable) {
      return $result;
    }

    $tableName = $this->stockIndexTableProvider->execute($stockId);

    $collection->getSelect()->joinLeft(
      ['msi_stock_status_index' => $tableName],
      'e.sku = msi_stock_status_index.sku',
      ['is_salable' => 'IFNULL(msi_stock_status_index.is_salable, 0)']
    );

    return $result;
  }
}
