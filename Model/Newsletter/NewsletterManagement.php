<?php

namespace Appstractsoftware\MagentoAdapter\Model\Newsletter;

use Magento\Customer\Api\AccountManagementInterface as CustomerAccountManagement;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\DataObject;
use Magento\Newsletter\Model\SubscriberFactory;

/**
 * {@inheritDoc}
 */
class NewsletterManagement implements \Appstractsoftware\MagentoAdapter\Api\Newsletter\NewsletterManagementInterface
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var CustomerAccountManagement
     */
    protected $customerAccountManagement;

    /**
     * @var SubscriberFactory
     */
    protected $_subscriberFactory;

    /**
     * Initialize dependencies.
     *
     * @param Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Session $customerSession
     * @param CustomerAccountManagement $customerAccountManagement
     * @param SubscriberFactory $subscriberFactory
     */
    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Session $customerSession,
        CustomerAccountManagement $customerAccountManagement,
        SubscriberFactory $subscriberFactory
    ) {
        $this->_objectManager = $context->getObjectManager();
        $this->_storeManager = $storeManager;
        $this->_customerSession = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->_subscriberFactory = $subscriberFactory;
    }

    /**
     * {@inheritDoc}
     * Reference: vendor/magento/module-newsletter/Controller/Subscriber/NewAction.php
     */
    public function subscribe($email)
    {
        if(!$this->isValidEmail($email)) {
          return (new DataObject())->setData([
            'message' => 'Please enter a valid email address',
            'status' => 'INVALID_EMAIL',
          ]);
        }

        if(!$this->isGuestSubscriptionEnabled()) {
          return (new DataObject())->setData([
            'message' => 'Sorry, but the administrator denied subscription for guests.',
            'status' => 'DISABLED',
          ]);
        }

        if($this->isEmailAlreadySubscribed($email)) {
          return (new DataObject())->setData([
            'message' => 'This email address is already subscribed.',
            'status' => 'ALREADY_SUBSCRIBED',
          ]);
        }

        try {
            $subscriber = $this->_subscriberFactory->create()->loadByEmail($email);
            if ($subscriber->getId()
                && $subscriber->getSubscriberStatus() == \Magento\Newsletter\Model\Subscriber::STATUS_SUBSCRIBED
            ) {
              return (new DataObject())->setData([
                'message' => 'This email address is already subscribed.',
                'status' => 'ALREADY_SUBSCRIBED',
              ]);
            }

            $status = $this->_subscriberFactory->create()->subscribe($email);
            if ($status == \Magento\Newsletter\Model\Subscriber::STATUS_NOT_ACTIVE) {
              return (new DataObject())->setData([
                'message' => 'The confirmation request has been sent.',
                'status' => 'NEEDS_CONFIRMATION',
              ]);
            } else {
              return (new DataObject())->setData([
                'message' => 'This email address is subscribed.',
                'status' => 'SUBSCRIBED',
              ]);
            }
        } catch (\Exception $e) {
          return (new DataObject())->setData([
            'message' => __('There was a problem with the subscription: %1', $e->getMessage()),
            'status' => 'ERROR',
          ]);
        }
    }

    /**
     * Validates the format of the email address
     * Reference: vendor/magento/module-newsletter/Controller/Subscriber/NewAction.php
     *
     * @param string $email
     * @return boolean
     */
    private function isValidEmail($email)
    {
        return (\Zend_Validate::is($email, \Magento\Framework\Validator\EmailAddress::class));
    }


    /**
     * Validates that if the current user is a guest, that they can subscribe to a newsletter.
     * Reference: vendor/magento/module-newsletter/Controller/Subscriber/NewAction.php
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return boolean
     */
    private function isGuestSubscriptionEnabled()
    {
        return ($this->_objectManager->get(\Magento\Framework\App\Config\ScopeConfigInterface::class)
                ->getValue(
                    \Magento\Newsletter\Model\Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ) == 1
        );
    }

    /**
     * Validates that the email address isn't being used by a different account.
     * Reference: vendor/magento/module-newsletter/Controller/Subscriber/NewAction.php
     *
     * @param string $email
     * @return boolean
     */
    private function isEmailAlreadySubscribed($email)
    {
        $websiteId = $this->_storeManager->getStore()->getWebsiteId();
        return ($this->_customerSession->getCustomerDataObject()->getEmail() !== $email
            && !$this->customerAccountManagement->isEmailAvailable($email, $websiteId)
        );
    }
}
