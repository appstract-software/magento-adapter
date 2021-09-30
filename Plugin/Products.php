<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class Products
{
  public function beforeResolve(
    $subject,
    Field $field,
    $context,
    ResolveInfo $info,
    array $value = null,
    array $args = null
  ) {
    if (array_key_exists('customSort', $args)) {
      $sorts = [];
      $tmpSorts = explode(',', $args['customSort']);

      foreach ($tmpSorts as $val) {
        $sort = explode(':', $val);

        if (count($sort) === 2) {
          $sorts[$sort[0]] = $sort[1];
        }
      }

      if (count($sorts) > 0) {
        $args['sort'] = $sorts;
      }
    }

    return [
      $field,
      $context,
      $info,
      $value,
      $args
    ];
  }
}
