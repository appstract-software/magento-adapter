<?php

namespace Appstractsoftware\MagentoAdapter\CustomGQL\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Sales\Model\Order;
use Magento\Framework\Exception\LocalizedException;

class ParcelLockerResolver implements ResolverInterface
{
  /**
   * @inheritDoc
   */
  public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
  {
    if (!isset($value['model']) && !($value['model'] instanceof Order)) {
      throw new LocalizedException(__('"model" value should be specified'));
    }
    /** @var Order $order */
    $order = $value['model'];
    $methodCode = $order->getShippingMethod();

    if ($methodCode == 'paczkomaty2' || $methodCode == 'paczkomaty2cod') {
      $paczkomatyData = json_decode($order->getPaczkomatyData());
      $shipmentGenerated = $paczkomatyData
        && property_exists($paczkomatyData, 'shipmentData')
        && $paczkomatyData->shipmentData
        && property_exists($paczkomatyData->shipmentData, 'custom_attributes')
        && $paczkomatyData->shipmentData->custom_attributes;

      if (!$shipmentGenerated) {
        return null;
      }

      return $paczkomatyData->shipmentData->custom_attributes->target_point;
    }

    return null;
  }
}
