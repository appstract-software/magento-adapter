<?php

namespace Appstractsoftware\MagentoAdapter\CustomGQL\Model\Resolver;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;


class OrderItemProductResolver implements ResolverInterface
{

  public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
  {
    if (!array_key_exists('associatedProduct', $value)) {
      return null;
    }

    if (!$value['associatedProduct'] instanceof ProductInterface) {
      throw new LocalizedException(__('"associatedProduct" value should be specified'));
    }

    /* @var $product ProductInterface */
    $product = $value['associatedProduct'];

    $productData = $product->getData();
    $productData['model'] = $product;

    return $productData;
  }
}
