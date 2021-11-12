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
    var_dump(222);
    $vars = [
      'store' => $subject->_storeManager->getStore($subject->getStoreId()),
      'subscriber_data' => [
        // 'confirmation_link' => $subject->getConfirmationLink(),
        'confirmation_code' => $subject->getCode(),
        'email' => $subject->getEmail(),
        // 'encoded_email' => base64_encode($subject->getEmail()),
      ],
    ];
    var_dump(333);
    $this->sendEmail(self::XML_PATH_CONFIRM_EMAIL_TEMPLATE, self::XML_PATH_CONFIRM_EMAIL_IDENTITY, $vars, $subject);

    return $this;
  }

  private function sendEmail(string $emailTemplatePath, string $emailIdentityPath, array $templateVars = [], $subject): void
  {
    if ($subject->getImportMode()) {
      return;
    }
    
    var_dump('#1');
    
    $template = $subject->_scopeConfig->getValue($emailTemplatePath, ScopeInterface::SCOPE_STORE, $subject->getStoreId());
    $identity = $subject->_scopeConfig->getValue($emailIdentityPath, ScopeInterface::SCOPE_STORE, $subject->getStoreId());

    if (!$template || !$identity) {
      return;
    }

    var_dump('#2');


    $templateVars += ['subscriber' => $subject];
    $subject->inlineTranslation->suspend();

    var_dump('#3');

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

    var_dump('#4');

    $transport = $subject->_transportBuilder->getTransport();
    var_dump('#5');

    $transport->sendMessage();
    var_dump('#6');

    $subject->inlineTranslation->resume();
    var_dump('#7');

  }
}
