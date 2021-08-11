<?php
namespace Appstractsoftware\MagentoAdapter\Plugin;

use \Magento\Customer\Model\CustomerFactory;
use \Magento\Customer\Api\GroupRepositoryInterface;

class Customer
{
    /**
     * @var CustomerFactory
     */
    private $customerFactory;
    
    /** 
     * @var GroupRepositoryInterface
     */
    private $groupRepository;

    public function __construct(
        CustomerFactory $customerFactory,
        GroupRepositoryInterface $groupRepository
    ) {
        $this->customerFactory = $customerFactory;
        $this->groupRepository = $groupRepository;
    }

    public function afterGetExtensionAttributes($customer, $result) {
        $customerGroup = $this->groupRepository->getById($customer->getGroupId());
        $groupName = $customerGroup->getCode();

        if (!$result) {
            $extensionAttributes = $this->customerFactory->create();
            $extensionAttributes->setGroupName($groupName);
            $customer->setExtensionAttributes($extensionAttributes);
        } else {
            $result->setGroupName($groupName);
        }

	    return $result;
    }
}
