<?php

namespace Appstractsoftware\MagentoAdapter\Model;


use Appstractsoftware\MagentoAdapter\Api\MailingServiceInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\ResourceConnection;

class MailingService extends AbstractHelper implements MailingServiceInterface
{
    // /**
    //  * @inheritDoc
    //  */


    protected $transportBuilder;
    protected $storeManager;
    protected $inlineTranslation;
    protected $resourceConnection;

    public function __construct(
        Context $context,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        ResourceConnection $resourceConnection,
        StateInterface $state

    ) {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->resourceConnection = $resourceConnection;
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

            $emailSended = $this->sendEmail($email, $templateId, $variables);

            $this->insertStatus($topic, $email, $name, $company, $message, $orderId);

            return $emailSended;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function insertStatus($topic, $email, $name, $company, $message, $orderId)
    {
        $connection  = $this->resourceConnection->getConnection();

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $data = [
            'topic' => $topic,
            'name' => $name,
            'company' => $company,
            'message' => $message,
            'orderId' => $orderId,
            'date' => date("Y-m-d H:i:s"),
            'ip' => $ip,
            'email' => $email
        ];

        $connection->insert('appstract_contact_form', $data);
    }
}
