<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Magento\Framework\Search\Dynamic\EntityStorage;
use Magento\Framework\Search\Request\BucketInterface;

class Auto
{
  public function aroundGetItems(
    \Magento\Framework\Search\Dynamic\Algorithm\Auto\Interceptor $subject,
    callable $proceed,
    BucketInterface $bucket,
    array $dimensions,
    EntityStorage $entityStorage
  ) {
    $data = [];
    $aggregations = $subject->dataProvider->getAggregations($entityStorage);

    $data[] = [
      'from' => $aggregations['min'],
      'to' => $aggregations['max'],
      'count' => 0,
    ];

    return $data;
  }
}
