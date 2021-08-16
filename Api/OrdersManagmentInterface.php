<?php

namespace Appstractsoftware\MagentoAdapter\Api;

interface OrdersManagmentInterface
{
   /**
    * Get orders for customer
    *
    * @param int $customerId
    * @param \Magento\Framework\Api\Search\SearchCriteriaInterface $searchCriteria
    * @return \Magento\Sales\Api\Data\OrderInterface[]
    */
   public function getListForCustomer($customerId, $searchCriteria);
}
