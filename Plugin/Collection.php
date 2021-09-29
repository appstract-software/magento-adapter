<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Magento\Framework\Data\Collection as DataCollection;

class Collection
{
  public function beforeAddAttributeToSort($subject, $attribute, $dir = DataCollection::SORT_ORDER_ASC)
  {
    if ($attribute == 'is_salable') {
      $attribute = 'is_saleable';
    }

    return [
      $attribute,
      $dir
    ];
  }
}
