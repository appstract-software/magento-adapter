<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\PayUOrderInterface;

class PayUOrder implements PayUOrderInterface
{
  public function __construct(
    \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
    \Magento\Framework\ObjectManagerInterface $objectManager,
    \Appstractsoftware\MagentoAdapter\Api\Data\PayUOrderCreateResponseInterface $payUOrderCreateResponse,
    \Appstractsoftware\MagentoAdapter\Model\ModuleLoader $moduleLoader
  ) {
    $this->createOrderResolver = $moduleLoader->create('PayU_PaymentGateway', '\PayU\PaymentGateway\Api\CreateOrderResolverInterface');
    $this->payUCreateOrder = $moduleLoader->create('PayU_PaymentGateway', '\PayU\PaymentGateway\Api\PayUCreateOrderInterface');
    $this->openPayUOrder = $moduleLoader->create('PayU_PaymentGateway', '\OpenPayU_Order');
    $this->payUConfig = $moduleLoader->create('PayU_PaymentGateway', '\PayU\PaymentGateway\Api\PayUConfigInterface');

    $this->orderRepository = $orderRepository;
    $this->objectManager = $objectManager;
    $this->payUOrderCreateResponse = $payUOrderCreateResponse;
  }

  /**
   * @inheritDoc
   */
  public function createOrder($orderId, $continueUrl)
  {
    if (is_null($this->createOrderResolver)) {
      throw new \Magento\Framework\Exception\LocalizedException(__('Module PayU_PaymentGateway is not enabled'));
    }

    $order = $this->orderRepository->get($orderId);

    $orderAdapter = $this->objectManager->create(\Magento\Payment\Gateway\Data\Order\OrderAdapter::class, ['order' => $order]);

    $createOrderData = $this->createOrderResolver->resolve(
      $orderAdapter,
      'PBL',
      '',
      $order->getGrandTotal(),
      $order->getOrderCurrencyCode(),
      $continueUrl
    );

    $response = $this->payUCreateOrder->execute('payu_gateway', $createOrderData);

    return $this->payUOrderCreateResponse->load($response);
  }

  /**
   * @inheritDoc
   */
  public function getOrderStatus($id)
  {
    if (is_null($this->createOrderResolver)) {
      throw new \Magento\Framework\Exception\LocalizedException(__('Module PayU_PaymentGateway is not enabled'));
    }

    $this->payUConfig->setDefaultConfig('payu_gateway');

    $data = $this->openPayUOrder::retrieve($id);
    $response = $data->getResponse();

    if ($response->orders && $response->orders[0]) {
      return $response->orders[0]->status;
    }

    return '';
  }
}
