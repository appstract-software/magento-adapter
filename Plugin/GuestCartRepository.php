<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Appstractsoftware\MagentoAdapter\Plugin\GuestCartItemRepository;
use \Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface;

use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\GuestCartRepositoryInterface;
use Magento\Quote\Model\GuestCart\GuestCartRepository\Interceptor;
use \Magento\Framework\Api\SearchResults;

class GuestCartRepository
{
    /** @var \Appstractsoftware\MagentoAdapter\Plugin\GuestCartItemRepository */
    private $guestCartItemRepository;
    

    /**
     * CartItemRepository constructor.
    * 
    * @param GuestCartItemRepository $guestCartItemRepository
    */
    public function __construct(GuestCartItemRepository $guestCartItemRepository)
    {
        $this->guestCartItemRepository = $guestCartItemRepository;
    }


    /**
     * Add cart options to extension attributes.
     * 
     * @param GuestCartRepositoryInterface $subject
     * @param \Magento\Quote\Api\Data\CartInterface $cart
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    public function afterGet(GuestCartRepositoryInterface $subject, \Magento\Quote\Api\Data\CartInterface $cart)
    {
        return $this->loadData($cart);
    }

    /**
     * Load extension attribute data.
     *
     * @param \Magento\Quote\Api\Data\CartInterface $cart
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    private function loadData($cart)
    {
        foreach ($cart->getItems() as &$cartItem) {
            $cartItem = $this->guestCartItemRepository->loadData($cartItem);
        }

        return $cart;
    }
}