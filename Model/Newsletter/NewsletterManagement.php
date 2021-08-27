<?php

namespace Appstractsoftware\MagentoAdapter\Model\Newsletter;

use Magento\Customer\Api\AccountManagementInterface as CustomerAccountManagement;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\DataObject;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;

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
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * Initialize dependencies.
     *
     * @param Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Session $customerSession
     * @param CustomerAccountManagement $customerAccountManagement
     * @param SubscriberFactory $subscriberFactory
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Session $customerSession,
        CustomerAccountManagement $customerAccountManagement,
        SubscriberFactory $subscriberFactory,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->_objectManager = $context->getObjectManager();
        $this->_storeManager = $storeManager;
        $this->_customerSession = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->_subscriberFactory = $subscriberFactory;
        $this->customerRepository = $customerRepository;
      }

    private function getCustomerByEmail($email) 
    {
      try {
        $customer = $this->customerRepository->get($email);

        return $customer;
      } catch (\Exception $e) {
        return null;
      }
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

            $customer = $this->getCustomerByEmail($email);
            if ($customer) {
              $customerSubscriber = $this->_subscriberFactory->create()->loadByCustomerId($customer->getId());
              $customerSubscriber->subscribeCustomerById($customer->getId());

              return (new DataObject())->setData([
                'message' => 'This email address is subscribed.',
                'status' => 'SUBSCRIBED',
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

    /**
     * {@inheritDoc}
     * Reference: vendor/magento/module-newsletter/Controller/Subscriber/Unsubscribe.php
     */
    public function unsubscribe($email)
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

      try {
        $subscriber = $this->_subscriberFactory->create()->loadByEmail($email);

        if (!$subscriber->getId()) {
          return (new DataObject())->setData([
            'message' => 'Subscription not found for given email.',
            'status' => 'WRONG_EMAIL',
          ]);
        }

        if ($subscriber->getId()
            && $subscriber->getSubscriberStatus() == \Magento\Newsletter\Model\Subscriber::STATUS_UNSUBSCRIBED
        ) {
          return (new DataObject())->setData([
            'message' => 'This email address is not subscribed.',
            'status' => 'NOT_SUBSCRIBED',
          ]);
        }

        $subscriber->unsubscribe();

        return (new DataObject())->setData([
          'message' => 'This email address is unsubscribed.',
          'status' => 'UNSUBSCRIBED',
        ]);
      } catch (\Exception $e) {
        return (new DataObject())->setData([
          'message' => __('There was a problem with the subscription: %1', $e->getMessage()),
          'status' => 'ERROR',
        ]);
      }
    }
}
