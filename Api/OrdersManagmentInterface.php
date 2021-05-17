<?php

namespace Appstractsoftware\MagentoAdapter\Api;

interface OrdersManagmentInterface
{
  /**
   * Get product links
   *
   * @param int $customerId
   * @return Appstractsoftware\MagentoAdapter\Api\OrdersManagmentInterface
   */
  public function getListForCustomer($customerId);
}
