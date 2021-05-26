<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\OrdersInterface;

class Orders implements OrdersInterface
{
  public function __construct(
    \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
    \Magento\Sales\Model\Order\Config $orderConfig
  ) {
    $this->orderRepository = $orderRepository;
    $this->orderConfig = $orderConfig;
  }

  protected function getStateForStatus($newStatus)
  {
    $states = $this->orderConfig->getStates();

    foreach (array_keys($states) as $state) {
      $statuses = $this->orderConfig->getStateStatuses($state);

      foreach (array_keys($statuses) as $status) {
        if ($status == $newStatus) {
          return $state;
        }
      }
    }

    return null;
  }


  /**
   * @inheritDoc
   */
  public function setStatus($orderId, $status)
  {
    $order = $this->orderRepository->get($orderId);
    $newState = $this->getStateForStatus($status);

    if (is_null($newState)) {
      throw new \Magento\Framework\Exception\InvalidArgumentException(__('status not found'));
    }

    $order->setState($newState);
    $order->setStatus($status);

    $this->orderRepository->save($order);

    return 'OK';
  }
}
