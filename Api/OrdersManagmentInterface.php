<?php

namespace Appstractsoftware\MagentoAdapter\Api;

interface OrdersManagmentInterface
{
  /**
   * Get orders for customer
   *
   * @param int $customerId
   * @return \Magento\Sales\Api\Data\OrderSearchResultInterface
   */
  public function getListForCustomer($customerId);
}
