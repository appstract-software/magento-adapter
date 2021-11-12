<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Magento\Framework\App\Area;
use Magento\Store\Model\ScopeInterface;
use Magento\Newsletter\Model\Subscriber as ParentSubscriber;

class Subscriber extends ParentSubscriber
{
  public function aroundSendConfirmationRequestEmail(
    $subject,
    callable $proceed
  ) {
    $vars = [
      'store' => $subject->_storeManager->getStore($subject->getStoreId()),
      'subscriber_data' => [
        'confirmation_link' => $subject->getConfirmationLink(),
        'confirmation_code' => $subject->getCode(),
        'email' => $subject->getEmail(),
        'encoded_email' => base64_encode($subject->getEmail()),
      ],
    ];

    $this->sendEmail(self::XML_PATH_CONFIRM_EMAIL_TEMPLATE, self::XML_PATH_CONFIRM_EMAIL_IDENTITY, $vars, $subject);

    return $this;
  }

  public function aroundSendUnsubscriptionEmail()
  {
    $this->sendEmail(self::XML_PATH_UNSUBSCRIBE_EMAIL_TEMPLATE, self::XML_PATH_UNSUBSCRIBE_EMAIL_IDENTITY);

    return $this;
  }

  private function sendEmail(string $emailTemplatePath, string $emailIdentityPath, array $templateVars = [], $subject): void
  {
    if ($subject->getImportMode()) {
      return;
    }
        
    $template = $subject->_scopeConfig->getValue($emailTemplatePath, ScopeInterface::SCOPE_STORE, $subject->getStoreId());
    $identity = $subject->_scopeConfig->getValue($emailIdentityPath, ScopeInterface::SCOPE_STORE, $subject->getStoreId());

    if (!$template || !$identity) {
      return;
    }

    $templateVars += ['subscriber' => $subject];
    $subject->inlineTranslation->suspend();
    $subject->_transportBuilder->setTemplateIdentifier(
      $template
    )->setTemplateOptions(
      [
        'area' => Area::AREA_FRONTEND,
        'store' => $subject->getStoreId(),
      ]
    )->setTemplateVars(
      $templateVars
    )->setFrom(
      $identity
    )->addTo(
      $subject->getEmail(),
      $subject->getName()
    );
    $transport = $subject->_transportBuilder->getTransport();
    $transport->sendMessage();
    $subject->inlineTranslation->resume();
  }
}
