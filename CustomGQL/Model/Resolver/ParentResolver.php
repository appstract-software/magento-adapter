<?php

namespace Appstractsoftware\MagentoAdapter\CustomGQL\Model\Resolver;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;


class ParentResolver implements ResolverInterface
{

  public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
  {
    if (!array_key_exists('parent', $value)) {
      return null;
    }

    if (!$value['parent'] instanceof ProductInterface) {
      throw new LocalizedException(__('"parent" value should be specified'));
    }

    /* @var $product ProductInterface */
    $product = $value['parent'];

    $productData = $product->getData();
    $productData['model'] = $product;

    return $productData;
  }
}
