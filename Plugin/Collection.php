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

  public function afterAddAttributeToSort($subject, $result, $attribute, $dir = DataCollection::SORT_ORDER_ASC)
  {
    if ($attribute == 'created_at') {
      $subject->getSelect()->order('created_at ' . $dir);
      return $subject;
    }

    return $result;
  }
}
