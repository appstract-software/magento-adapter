<?php

namespace Appstractsoftware\MagentoAdapter\Model\Quote;

use Appstractsoftware\MagentoAdapter\Api\ShippingAddressManagementServiceInterface;
use \Magento\Quote\Model\ShippingAddressManagement as ShippingAddressManagement;

class ShippingAddressManagementService extends ShippingAddressManagement implements ShippingAddressManagementServiceInterface
{
}
