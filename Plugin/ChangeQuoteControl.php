<?php
namespace Appstractsoftware\MagentoAdapter\Plugin;

use \Magento\Authorization\Model\UserContextInterface;
use \Magento\Customer\Api\CustomerRepositoryInterface;
use \Magento\Customer\Api\GroupRepositoryInterface;
use \Magento\Quote\Api\Data\CartInterface;
use \Magento\Quote\Api\ChangeQuoteControlInterface;

class ChangeQuoteControl
{
    /**
     * @var UserContextInterface $userContext
     */
    private $userContext;

    /**
     * @var GroupRepositoryInterface $groupRepository
     */
    private $groupRepository;
    
    /**
     * @var CustomerRepositoryInterface $customerRepository
     */
    private $customerRepository;

    /**
     * @param UserContextInterface $userContext
     * @param GroupRepositoryInterface $groupRepository
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        UserContextInterface $userContext, 
        GroupRepositoryInterface $groupRepository, 
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->userContext = $userContext;
        $this->groupRepository = $groupRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Check if customer is in admin group and if he is allowed to perform admin actions.
     * 
     * @param ChangeQuoteControlInterface $subject
     * @param ChangeQuoteControl $result
     * @param CartInterface $quote
     * @return bool
     */
    public function afterIsAllowed(ChangeQuoteControlInterface $subject, $result, CartInterface $quote): bool
    {
        if ($result) {
            return $result;
        }

        switch ($this->userContext->getUserType()) {
            case UserContextInterface::USER_TYPE_CUSTOMER:
                $customerId = $this->userContext->getUserId();
                $customer = $this->customerRepository->getById($customerId);
                $groupId = $customer->getGroupId();
                $group = $this->groupRepository->getById($groupId);

                // TODO: Change hardcoded groupId.
                $isCustomerInAdminGroup = $groupId === '5';
                $isAllowed = $isCustomerInAdminGroup;
                
                break;
            default:
                $isAllowed = $result;
        }

        return $isAllowed;
    }
}
