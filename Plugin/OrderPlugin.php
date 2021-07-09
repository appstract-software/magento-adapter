<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Appstractsoftware\MagentoAdapter\Plugin;

class OrderPlugin
{
  /**
   * [afterSave description]
   * @param  \Magento\Sales\Model\ResourceModel\Order $subject [description]
   * @param  [type]                                   $result  [description]
   * @param  [type]                                   $object  [description]
   * @return [type]                                            [description]
   */
  public function afterSave(
    \Magento\Sales\Model\ResourceModel\Order $subject,
    $result,
    $object
  ) {
    var_dump($object);
    var_dump($result);
    return $result;
  }
}
