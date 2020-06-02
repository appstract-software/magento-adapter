<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Appstractsoftware\MagentoAdapter\Plugin\GuestCartItemRepository;
use \Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface;

use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Cart\CartRepository\Interceptor;
use \Magento\Framework\Api\SearchResults;

class CartRepository
{
    /** @var \Appstractsoftware\MagentoAdapter\Plugin\GuestCartItemRepository */
    private $guestGuestCartItemRepository;
    

    /**
     * CartRepository constructor.
    * 
    * @param GuestCartItemRepository $guestGuestCartItemRepository
    */
    public function __construct(GuestCartItemRepository $guestCartItemRepository)
    {
        $this->guestCartItemRepository = $guestCartItemRepository;
    }

    /**
     * Add cart options to extension attributes.
     * 
     * @param CartRepositoryInterface $subject
     * @param \Magento\Quote\Api\Data\CartInterface $cart
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    public function afterGetCartForCustomer(\Magento\Quote\Api\CartManagementInterface $subject, $cart)
    {
        foreach ($cart->getItems() as &$item) {
            $this->guestCartItemRepository->loadData($item);
        }

        return $cart;
    }
}
