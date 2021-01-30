<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\CustomerGroupInterface;
use Magento\Quote\Model\GuestCart\GuestCartRepository;

class CustomerGroup extends GuestCartRepository implements CustomerGroupInterface
{
  // /**
  //  * @inheritDoc
  //  */

   
  public function setCustomerGroup($cartId, $customerGroupId)
  {
    try {
        $cart = $this->get($cartId);
        $cart->setData('customer_group_id', $customerGroupId);
        $this->quoteRepository->save($cart);
        $response = ['success' => 'true'];
    } catch (\Exception $e) {
        $response = ['error' => 'true', 'message' => $e->getMessage()];
    }

    // TODO: Change this to JsonFactory from Magento
    return json_encode($response);

  }
}
