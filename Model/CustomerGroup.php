<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\CustomerGroupInterface;
use Magento\Quote\Model\GuestCart\GuestCartRepository;

class CustomerGroup extends GuestCartRepository implements CustomerGroupInterface
{
  /**
   * @inheritDoc
   */

  public function setCustomerGroup($cartId, $customerGroupId)
  {
    $cart = $this->get($cartId);
    $cart->setData('customer_group_id', $customerGroupId);
    $this->quoteRepository->save($cart);

    return 'success';
  }
}
