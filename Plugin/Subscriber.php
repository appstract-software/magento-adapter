<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Magento\Framework\App\Area;
use Magento\Store\Model\ScopeInterface;
use Magento\Newsletter\Model\Subscriber as ParentSubscriber;

class Subscriber extends ParentSubscriber
{
  public function aroundSndConfirmationRequestEmail(
    $subject,
    callable $proceed
  ) {
    $vars = [
      'store' => $this->_storeManager->getStore($this->getStoreId()),
      'subscriber_data' => [
        'confirmation_link' => $this->getConfirmationLink(),
        'confirmation_code' => $this->getCode(),
        'email' => $this->getEmail(),
        'encoded_email' => base64_encode($this->getEmail()),
      ],
    ];
    $this->sendEmail(self::XML_PATH_CONFIRM_EMAIL_TEMPLATE, self::XML_PATH_CONFIRM_EMAIL_IDENTITY, $vars);
    return $this;
  }
  
  /**
   * Send email about change status
   *
   * @param string $emailTemplatePath
   * @param string $emailIdentityPath
   * @param array $templateVars
   * @return void
   */
  private function sendEmail(string $emailTemplatePath, string $emailIdentityPath, array $templateVars = []): void
  {
    if ($this->getImportMode()) {
      return;
    }
    $template = $this->_scopeConfig->getValue($emailTemplatePath, ScopeInterface::SCOPE_STORE, $this->getStoreId());
    $identity = $this->_scopeConfig->getValue($emailIdentityPath, ScopeInterface::SCOPE_STORE, $this->getStoreId());
    if (!$template || !$identity) {
      return;
    }
    $templateVars += ['subscriber' => $this];
    $this->inlineTranslation->suspend();
    $this->_transportBuilder->setTemplateIdentifier(
      $template
    )->setTemplateOptions(
      [
        'area' => Area::AREA_FRONTEND,
        'store' => $this->getStoreId(),
      ]
    )->setTemplateVars(
      $templateVars
    )->setFrom(
      $identity
    )->addTo(
      $this->getEmail(),
      $this->getName()
    );
    $transport = $this->_transportBuilder->getTransport();
    $transport->sendMessage();
    $this->inlineTranslation->resume();
  }
}
