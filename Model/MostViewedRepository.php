<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\MostViewedRepositoryInterface;

class MostViewedRepository extends ProductRepository implements MostViewedRepositoryInterface
{

  /**
   * @inheritDoc
   */
  public function getList($limit = 10)
  {
    $collection = $this->getProductCollection()
      ->addViewsCount()
      ->setPageSize($limit);

    $collection->load();
    $collection->addCategoryIds();

    $searchResults = $this->searchResultsFactory->create();
    $searchResults->setItems($collection->getItems());
    $searchResults->setTotalCount($collection->count());

    return $searchResults;
  }
}
