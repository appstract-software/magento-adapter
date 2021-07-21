<?php
namespace Appstractsoftware\MagentoAdapter\Model;

use \Magento\Quote\Api\CartManagementInterface;
use \Magento\Quote\Api\CartRepositoryInterface;
use \Magento\Quote\Api\GuestCartManagementInterface;
use \Magento\Quote\Api\GuestCartRepositoryInterface;
use \Magento\Quote\Api\CartTotalRepositoryInterface;
use \Magento\Customer\Api\CustomerRepositoryInterface;

use \Appstractsoftware\MagentoAdapter\Api\PreparedCartRepositoryInterface;

/**
 * Cart management class for prepared carts.
 */
class PreparedCartRepository implements PreparedCartRepositoryInterface
{
    /**
     * @var CartManagementInterface
     */
    private $cartManagement;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var GuestCartManagementInterface
     */
    private $guestCartManagement;

    /**
     * @var GuestCartRepositoryInterface
     */
    private $guestCartRepository;

    /**
     * @var CartTotalRepositoryInterface
     */
    private $cartTotalRepositoryInterface;

    /**
     * Initialize dependencies.
     *
     * @param CartManagementInterface $cartManagement
     * @param CartRepositoryInterface $cartRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param GuestCartManagementInterface $quoteManagement
     * @param GuestCartRepositoryInterface $guestCartRepository
     * @param CartTotalRepositoryInterface $cartTotalRepositoryInterface
     */
    public function __construct(
        CartManagementInterface $cartManagement,
        CartRepositoryInterface $cartRepository,
        CustomerRepositoryInterface $customerRepository,
        GuestCartManagementInterface $guestCartManagement,
        GuestCartRepositoryInterface $guestCartRepository,
        CartTotalRepositoryInterface $cartTotalRepositoryInterface
    ) {
        $this->cartManagement = $cartManagement;
        $this->cartRepository = $cartRepository;
        $this->customerRepository = $customerRepository;
        $this->guestCartManagement = $guestCartManagement;
        $this->guestCartRepository = $guestCartRepository;
        $this->cartTotalRepositoryInterface = $cartTotalRepositoryInterface;
    }

    /**
     * @inheritDoc
     */
    public function createGuestCartBasedOnAdminCustomerCart($cartId)
    {
        $preparedCart = $this->cartRepository->get($cartId);

        $newCartId = $this->guestCartManagement->createEmptyCart();
        $newCart = $this->guestCartRepository->get($newCartId);
        $newCart->merge($preparedCart);

        $this->cartRepository->save($newCart);
        $this->cartRepository->save($preparedCart);
        
        return $newCartId;    
    }

    /**
     * @inheritDoc
     */
    public function emptyPreparedAdminCustomerCart($customerId, $cartId)
    {
        $customerCart = $this->cartRepository->getForCustomer($customerId);
        $customerCart->setIsActive(false);
        $this->cartRepository->save($customerCart);

        $newCustomerCartId = $this->cartManagement->createEmptyCartForCustomer($customerId);
        $newCustomerCart = $this->cartRepository->get($newCustomerCartId);
        $this->cartRepository->save($newCustomerCart);

        return $newCustomerCart->getId();
    }

    /**
     * @inheritDoc
     */
    public function applyPreparedQuestCartToCustomerCart($preparedCartId, $customerId)
    {
        $preparedCart = $this->guestCartRepository->get($preparedCartId);

        $customerCart = $this->cartRepository->getForCustomer($customerId);
        $customerCart->setIsActive(false);
        $this->cartRepository->save($customerCart);

        $newCustomerCartId = $this->cartManagement->createEmptyCartForCustomer($customerId);
        $newCustomerCart = $this->cartRepository->get($newCustomerCartId);
        $newCustomerCart->merge($preparedCart);

        $this->cartRepository->save($newCustomerCart);
        $this->cartRepository->save($preparedCart);

        return $newCustomerCart->getId();
    }
}
