<?php

namespace Appstractsoftware\MagentoAdapter\Api;

interface OrdersManagmentInterface
{
   /**
    * Get orders for customer
    *
    * @param int $customerId
    * @return \Magento\Sales\Api\Data\OrderInterface[]
    */
   public function getListForCustomer($customerId);
}
