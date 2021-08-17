<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\Przelewy24Interface;

class Przelewy24 implements Przelewy24Interface
{
  public function __construct(
    \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
    \Magento\Framework\ObjectManagerInterface $objectManager,
    \Appstractsoftware\MagentoAdapter\Api\Data\PayUOrderCreateResponseInterface $payUOrderCreateResponse,
    \Appstractsoftware\MagentoAdapter\Model\ModuleLoader $moduleLoader
  ) {
    $this->przelewy = $moduleLoader->create('Dialcom_Przelewy', '\Dialcom\Przelewy\Model\Payment\Przelewy');
    $this->helper = $moduleLoader->create('Dialcom_Przelewy', '\Dialcom\Przelewy\Helper\Data');
    $this->orderRepository = $orderRepository;
    $this->objectManager = $objectManager;
    $this->payUOrderCreateResponse = $payUOrderCreateResponse;
  }

  /**
   * @inheritDoc
   */
  public function registerTransaction($orderId, $urlReturn)
  {
    if (is_null($this->przelewy)) {
      throw new \Magento\Framework\Exception\LocalizedException(__('Module Dialcom_Przelewy is not enabled'));
    }

    $order = $this->objectManager->create('Magento\Sales\Model\Order')->load($orderId);

    if (!$order->getId()) {
      throw new \Magento\Framework\Exception\NotFoundException(__('Order not found'));
    }

    $store_id = $order->getStoreId();
    $fullConfig = \Dialcom\Przelewy\Model\Config\Waluty::getFullConfig($order->getOrderCurrencyCode(), null, $store_id);

    $P24C = new \Dialcom\Przelewy\Przelewy24Class(
      $fullConfig['merchant_id'],
      $fullConfig['shop_id'],
      $fullConfig['salt'],
      ($this->helper->getConfig(\Dialcom\Przelewy\Helper\Data::XML_PATH_MODE) == '1')
    );
    $postData = $this->przelewy->getRedirectionFormData($orderId);
    $postData['p24_url_return'] = $urlReturn;

    foreach ($postData as $k => $v) {
      $P24C->addValue($k, $v);
    }

    $data = $P24C->trnRegister();

    if ($data['error'] !== 0) {
      throw new \Magento\Framework\Exception\LocalizedException(__($data['errorMessage'] || 'Something went wrong'));
    }

    return $data['token'];
  }
}
