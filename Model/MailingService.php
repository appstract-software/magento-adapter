<?php

namespace Appstractsoftware\MagentoAdapter\Model;


use Appstractsoftware\MagentoAdapter\Api\MailingServiceInterface;
// use Magento\Framework\Mail\Template\TransportBuilder;
use Appstractsoftware\MagentoAdapter\Model\TransportBuilder;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Mail\MimePartInterfaceFactory;

class MailingService extends AbstractHelper implements MailingServiceInterface
{
    // /**
    //  * @inheritDoc
    //  */


    protected $transportBuilder;
    protected $storeManager;
    protected $inlineTranslation;
    protected $resourceConnection;
    protected $mimeFactory;

    public function __construct(
        Context $context,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        ResourceConnection $resourceConnection,
        StateInterface $state,
        MimePartInterfaceFactory $mimeFactory

    ) {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->resourceConnection = $resourceConnection;
        $this->inlineTranslation = $state;
        $this->mimeFactory = $mimeFactory;
        parent::__construct($context);
    }

    public function sendEmail($email, $templateId, $variables, $attachments = [])
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
                ->addTo($email);

            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    $transport->addAttachment($attachment->getContent(), $attachment->getFileName(), $attachment->getFileType(), $attachment->getDisposition(), $attachment->getEncoding());
                }
            }

            $transport->getTransport()->sendMessage();
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

        $time = time();

        $data = [
            'topic' => $topic,
            'name' => $name,
            'company' => $company,
            'message' => $message,
            'orderId' => $orderId,
            'date' => $time,
            'ip' => $ip,
            'email' => $email
        ];

        $connection->insert('appstract_contact_form', $data);
    }
}
