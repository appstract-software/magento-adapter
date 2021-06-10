<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Payment\Transaction;
use Magento\Sales\Model\Order\Payment\Operations\AbstractOperation;

class RegisterCaptureNotificationOperation extends AbstractOperation
{

  /**
   * @var \Appstractsoftware\MagentoAdapter\Helper\Data
   */
  private $helper;

  public function __construct(
    \Appstractsoftware\MagentoAdapter\Helper\Data $helper,
    \Magento\Store\Model\StoreManagerInterface $storeManager
  ) {
    $this->helper = $helper;
    $this->storeManager = $storeManager;
  }

  /**
   * Registers capture notification.
   *
   * @param OrderPaymentInterface $payment
   * @param string|float $amount
   * @param bool|int $skipFraudDetection
   * @return OrderPaymentInterface
   */
  public function aroundRegisterCaptureNotification($subject, callable $proceed, OrderPaymentInterface $payment, $amount, $skipFraudDetection = false)
  {
    /**
     * @var $payment Payment
     */
    $payment->setTransactionId(
      $this->transactionManager->generateTransactionId(
        $payment,
        Transaction::TYPE_CAPTURE,
        $payment->getAuthorizationTransaction()
      )
    );

    $order = $payment->getOrder();
    $amount = (float)$amount;
    $invoice = $this->getInvoiceForTransactionId($order, $payment->getTransactionId());

    // register new capture
    if (!$invoice) {
      if ($payment->isSameCurrency() && $payment->isCaptureFinal($amount)) {
        $autoGenerateInvoice = $this->helper->getAutoGenerateInvoice($this->storeManager->getStore()->getId());

        if ($autoGenerateInvoice) {
          $invoice = $order->prepareInvoice()->register();
          $invoice->setOrder($order);
          $order->addRelatedObject($invoice);
          $payment->setCreatedInvoice($invoice);
          $payment->setShouldCloseParentTransaction(true);
        }
      } else {
        $payment->setIsFraudDetected(!$skipFraudDetection);
        $this->updateTotals($payment, ['base_amount_paid_online' => $amount]);
      }
    }

    if (!$payment->getIsTransactionPending()) {
      if ($invoice && Invoice::STATE_OPEN == $invoice->getState()) {
        $invoice->setOrder($order);
        $invoice->pay();
        $this->updateTotals($payment, ['base_amount_paid_online' => $amount]);
        $order->addRelatedObject($invoice);
      }
    }

    $message = $this->stateCommand->execute($payment, $amount, $order);
    $transaction = $payment->addTransaction(
      Transaction::TYPE_CAPTURE,
      $invoice,
      true
    );
    $message = $payment->prependMessage($message);
    $payment->addTransactionCommentsToOrder($transaction, $message);
    return $payment;
  }
}
