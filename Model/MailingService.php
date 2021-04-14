<?php

namespace Appstractsoftware\MagentoAdapter\Model;


use Appstractsoftware\MagentoAdapter\Api\MailingServiceInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;

class MailingService extends AbstractHelper implements MailingServiceInterface
{
  // /**
  //  * @inheritDoc
  //  */


  protected $transportBuilder;
  protected $storeManager;
  protected $inlineTranslation;

  public function __construct(
      Context $context,
      TransportBuilder $transportBuilder,
      StoreManagerInterface $storeManager,
      StateInterface $state
  )
  {
      $this->transportBuilder = $transportBuilder;
      $this->storeManager = $storeManager;
      $this->inlineTranslation = $state;
      parent::__construct($context);
  }

  public function sendMail($mail, $templateId, $variables)
  {
      // Getting mail from Magento Store Settings
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $scopeConfig = $objectManager->create('\Magento\Framework\App\Config\ScopeConfigInterface');
      $email = $scopeConfig->getValue('trans_email/ident_general/email',\Magento\Store\Model\ScopeInterface::SCOPE_STORE); 

      try {
          $store = $this->storeManager->getStore();
          $storeId = $store->getId();
          $from = ['email' => $email, 'name' => $name];
          $this->inlineTranslation->suspend();

          $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
          $templateOptions = [
              'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
              'store' => $storeId
          ];
          $transport = $this->transportBuilder->setTemplateIdentifier($templateId, $storeScope)
              ->setTemplateOptions($templateOptions)
              ->setTemplateVars($variables)
              ->setFrom($from)
              ->addTo($mail)
              ->getTransport();
          $transport->sendMessage();
          $this->inlineTranslation->resume();
          return $storeId;
      } catch (\Exception $e) {
          return $e->getMessage();
      }
    }
}

