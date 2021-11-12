<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\InPostInterface;

class InPost implements InPostInterface
{
  public function __construct(
    \Appstractsoftware\MagentoAdapter\Model\ModuleLoader $moduleLoader,
    \Appstractsoftware\MagentoAdapter\Model\Orders $orders,
    \Appstractsoftware\MagentoAdapter\Helper\Data $helper,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Sales\Model\OrderFactory $orderFactory
  ) {
    $this->paczkomatyHelper = $moduleLoader->create('Smartmage_Paczkomaty2', 'Smartmage\Paczkomaty2\Helper\Data');
    $this->orders = $orders;
    $this->helper = $helper;
    $this->storeManager = $storeManager;
    $this->orderFactory = $orderFactory;
  }

  /**
   * @inheritDoc
   */
  public function updateOrderStatus($shipmentId, $status)
  {
    if (is_null($this->paczkomatyHelper)) {
      throw new \Magento\Framework\Exception\LocalizedException(__('Module Smartmage_Paczkomaty2 is not enabled'));
    }

    $shipment = $this->paczkomatyHelper->getShipmentData($shipmentId);

    $newStatus = '';
    $storeId = $this->storeManager->getStore()->getId();

    switch ($status) {
      case 'collected_from_sender':
      case 'taken_by_courier':
        $newStatus = $this->helper->getInPostSentStatus($storeId);
        break;
      case 'ready_to_pickup_from_pok':
      case 'ready_to_pickup_from_pok_registered':
      case 'ready_to_pickup':
        $newStatus = $this->helper->getInPostReadyToPickupStatus($storeId);
        break;
      case 'delivered':
        $newStatus = $this->helper->getInPostDeliveredStatus($storeId);
        break;
    }

    if ($newStatus == '') {
      return 'NOT_OK';
    }

    $incrementId = str_replace('#', '', $shipment->reference);
    $orderModel = $this->orderFactory->create();
    $order = $orderModel->loadByIncrementId($incrementId);

    return $this->orders->setStatus($order->getId(), $newStatus);
  }
}
