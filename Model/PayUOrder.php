<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\PayUOrderInterface;

class PayUOrder implements PayUOrderInterface
{
  public function __construct(
    \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
    \Magento\Framework\ObjectManagerInterface $objectManager,
    \PayU\PaymentGateway\Api\CreateOrderResolverInterface $createOrderResolver,
    \PayU\PaymentGateway\Api\PayUCreateOrderInterface $payUCreateOrder,
    \Appstractsoftware\MagentoAdapter\Api\Data\PayUOrderCreateResponseInterface $payUOrderCreateResponse
  ) {
    $this->orderRepository = $orderRepository;
    $this->objectManager = $objectManager;
    $this->createOrderResolver = $createOrderResolver;
    $this->payUCreateOrder = $payUCreateOrder;
    $this->payUOrderCreateResponse = $payUOrderCreateResponse;
  }

  /**
   * @inheritDoc
   */
  public function createOrder($orderId, $continueUrl)
  {
    $order = $this->orderRepository->get($orderId);
    $orderAdapter = $this->objectManager->create(\Magento\Payment\Gateway\Data\Order\OrderAdapter::class, ['order' => $order]);

    $createOrderData = $this->createOrderResolver->resolve(
      $orderAdapter,
      '',
      '',
      $order->getGrandTotal(),
      $order->getOrderCurrencyCode(),
      $continueUrl
    );

    $response = $this->payUCreateOrder->execute('payu_gateway', $createOrderData);

    return $this->payUOrderCreateResponse->load($response);
  }
}
