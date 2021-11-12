<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Magento\Framework\App\Area;
use Magento\Store\Model\ScopeInterface;
use Magento\Newsletter\Model\Subscriber as ParentSubscriber;

class Subscriber extends ParentSubscriber
{
  // const XML_PATH_CONFIRM_EMAIL_TEMPLATE = 'newsletter/subscription/confirm_email_template';
  // const XML_PATH_CONFIRM_EMAIL_IDENTITY = 'newsletter/subscription/confirm_email_identity'; 
  // const XML_PATH_SUCCESS_EMAIL_TEMPLATE = 'newsletter/subscription/success_email_template';
  // const XML_PATH_SUCCESS_EMAIL_IDENTITY = 'newsletter/subscription/success_email_identity';
  // const XML_PATH_UNSUBSCRIBE_EMAIL_TEMPLATE = 'newsletter/subscription/un_email_template';
  // const XML_PATH_UNSUBSCRIBE_EMAIL_IDENTITY = 'newsletter/subscription/un_email_identity';
  // const XML_PATH_CONFIRMATION_FLAG = 'newsletter/subscription/confirm';
  // const XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG = 'newsletter/subscription/allow_guest_subscribe';  

  public function aroundSendConfirmationRequestEmail(
    $subject,
    callable $proceed
  ) {
    var_dump('JESTEM TU ELO aroundSendConfirmationRequestEmail');
    // var_dump($this->getCode());
    // var_dump($this->getEmail());
    var_dump($subject->getEmail());
    var_dump($subject->getCode());
    var_dump($subject->getConfirmationLink());
var_dump($subject->getStoreId());
    $vars = [
      'store' => $subject->_storeManager->getStore($subject->getStoreId()),
      'subscriber_data' => [
        'confirmation_link' => $subject->getConfirmationLink(),
        'confirmation_code' => $subject->getCode(),
        'email' => $subject->getEmail(),
        // 'encoded_email' => base64_encode($this->getEmail()),
      ],
    ];
    // var_dump($vars);
    // var_dump(self::XML_PATH_CONFIRM_EMAIL_TEMPLATE);
    // var_dump(self::XML_PATH_CONFIRM_EMAIL_IDENTITY);

    $this->sendEmail(self::XML_PATH_CONFIRM_EMAIL_TEMPLATE, self::XML_PATH_CONFIRM_EMAIL_IDENTITY, $vars);
    return $this;
  }

  // public function aroundSendConfirmationSuccessEmail()
  // {
  //   var_dump('JESTEM TU ELO sendConfirmationSuccessEmail');

  //    $this->sendEmail(self::XML_PATH_SUCCESS_EMAIL_TEMPLATE, self::XML_PATH_SUCCESS_EMAIL_IDENTITY);

  //     return $this;
  // }

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
    var_dump('elo 1');

    if ($this->getImportMode()) {
      return;
    }

    var_dump('elo 2');

    $template = $this->_scopeConfig->getValue($emailTemplatePath, ScopeInterface::SCOPE_STORE, $this->getStoreId());
    $identity = $this->_scopeConfig->getValue($emailIdentityPath, ScopeInterface::SCOPE_STORE, $this->getStoreId());

    if (!$template || !$identity) {
      return;
    }
    var_dump('elo 3');

    $templateVars += ['subscriber' => $this];
    
    var_dump('elo 4');
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
