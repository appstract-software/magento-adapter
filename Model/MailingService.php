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

    ) {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $state;
        parent::__construct($context);
    }

    public function sendEmail($email, $templateId, $variables)
    {
        // Getting mail from Magento Store Settings
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $scopeConfig = $objectManager->create('\Magento\Framework\App\Config\ScopeConfigInterface');
        $storeEmail = $scopeConfig->getValue('trans_email/ident_general/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $storeName = $scopeConfig->getValue('trans_email/ident_general/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        try {
            $store = $this->storeManager->getStore();
            $storeId = $store->getId();
            $from = ['email' => $storeEmail, 'name' => $storeName];
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
                ->addTo($email)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();

            return $storeId;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function sendContactEmail($email, $templateId, $variables, $topic, $name, $company, $message, $orderId = '')
    {
        try {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            $emailSended = $this->sendEmail($email, $templateId, $variables);

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $resourceConnection = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resourceConnection->getConnection();
            $themeTable = $resourceConnection->getTableName('appstract_contact_form');


            $sql = "INSERT INTO " . $themeTable . "(topic, email, name, company, message, orderId, date, ip) VALUES ('"
                . $topic
                . "', '" . $email
                . "', '" . $name
                . "', '" . $company
                . "', '" . $message
                . "', '" . $orderId
                . "', '" . date("Y-m-d H:i:s")
                . "', '" . $ip . "')";

            $connection->query($sql);

            return $emailSended;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
