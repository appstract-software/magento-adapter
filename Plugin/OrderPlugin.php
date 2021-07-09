<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Appstractsoftware\MagentoAdapter\Plugin;

class OrderPlugin
{

  /** @var \Magento\Sales\Api\OrderRepositoryInterface */
  private $orderRepository;

  public function __construct(
    \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
  ) {
    $this->orderRepository = $orderRepository;
  }

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
    $order = $this->orderRepository->get($result);
    $orderIncrementId = $order->getIncrementId();

    var_dump($orderIncrementId);
    return $result;
  }
}
