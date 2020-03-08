<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\NewProductsRepositoryInterface;

/**
 * NewProductsRepository
 * 
 * @author Piotr Szuttenbach <piotr.szuttenbach@appstract.software>
 * @copyright 2020 Appstract Software
 */
class NewProductsRepository extends ProductRepository implements NewProductsRepositoryInterface
{

  /**
   * @inheritDoc
   */
  public function getList($limit = 10)
  {
    $collection = $this->getProductCollection();
    $todayStartOfDayDate = $this->localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
    $todayEndOfDayDate = $this->localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');

    $collection->addAttributeToFilter(
      'news_from_date',
      [
        'or' => [
          0 => ['date' => true, 'to' => $todayEndOfDayDate],
          1 => ['is' => new \Zend_Db_Expr('null')],
        ]
      ],
      'left'
    )->addAttributeToFilter(
      'news_to_date',
      [
        'or' => [
          0 => ['date' => true, 'from' => $todayStartOfDayDate],
          1 => ['is' => new \Zend_Db_Expr('null')],
        ]
      ],
      'left'
    )->addAttributeToFilter(
      [
        ['attribute' => 'news_from_date', 'is' => new \Zend_Db_Expr('not null')],
        ['attribute' => 'news_to_date', 'is' => new \Zend_Db_Expr('not null')],
      ]
    )->addAttributeToSort(
      'news_from_date',
      'desc'
    )->setPageSize(
      $limit
    )->setCurPage(
      1
    );

    $collection->load();
    $collection->addCategoryIds();

    $searchResults = $this->searchResultsFactory->create();
    $searchResults->setItems($collection->getItems());
    $searchResults->setTotalCount($collection->count());

    return $searchResults;
  }
}
