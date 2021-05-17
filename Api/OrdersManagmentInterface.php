<?php

namespace Appstractsoftware\MagentoAdapter\Api;

interface OrdersManagmentInterface
{
  /**
   * Get product links
   *
   * @param int $customerId
   * @return \Magento\Sales\Api\Data\OrderSearchResultInterface
   */
  public function getListForCustomer($customerId);
}
