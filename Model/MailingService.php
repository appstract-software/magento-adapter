<?php

namespace Appstractsoftware\MagentoAdapter\Model;


use Appstractsoftware\MagentoAdapter\Api\MailingServiceInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\ObjectManager;

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
        $name = $scopeConfig->getValue('trans_email/ident_general/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        try {
            $store = $this->storeManager->getStore();
            $storeId = $store->getId();
            $from = ['email' => $storeEmail, 'name' => $name];
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

            $resourceConnection = $objectManager->get('Magento\Framework\App\ResourceConnection');

            $connection = $resourceConnection->getConnection();
            $themeTable = $resourceConnection->getTableName('appstract_contact_form');

            var_dump($variables);

            $sql = "INSERT INTO " . $themeTable . "(topic, email, name, message, orderId, date, status, ip) VALUES ('"
                . $this->getValueFromArray($variables, 'topic')
                . "', '" . $email
                . "', '" . $this->getValueFromArray($variables, 'name')
                . "', '" . $this->getValueFromArray($variables, 'message')
                . "', '" . $this->getValueFromArray($variables, 'orderId')
                . "', '" . $this->getValueFromArray($variables, 'date')
                . "', '" . $this->getValueFromArray($variables, 'status')
                . "', '" . $this->getValueFromArray($variables, 'ip') . "')";
            $connection->query($sql);

            return $storeId;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function getValueFromArray($arr, $key)
    {
        return array_key_exists($key, $arr) ? $arr[$key] : null;
    }
}
