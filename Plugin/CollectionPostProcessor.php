<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Magento\Catalog\Model\ResourceModel\Product\Collection;

class CollectionPostProcessor
{

  public function afterProcess($subject, Collection $result)
  {
    $orders = $result->getSelect()->getPart(\Zend_Db_Select::ORDER);
    $newOrders = [];

    foreach ($orders as $order) {
      if (is_array($order) && $order[0] === 'msi_stock_status_index.quantity') {
        array_unshift($newOrders, $order);
      } else {
        $newOrders[] = $order;
      }
    }

    $result->getSelect()->setPart(\Zend_Db_Select::ORDER, $newOrders);

    return $result;
  }
}
