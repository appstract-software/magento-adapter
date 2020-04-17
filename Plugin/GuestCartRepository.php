<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use \Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface;

use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\GuestCartRepositoryInterface;
use Magento\Quote\Model\GuestCart\GuestCartRepository\Interceptor;
use \Magento\Framework\Api\SearchResults;

class GuestCartRepository
{
    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface */
    private $cartItemLinks;
    
    /**
     * @var \Magento\Quote\Api\GuestCartRepositoryInterface
     */
    protected $cartItemRepository;

    /**
     * CartItemRepository constructor.
    * 
    * @param CartItemLinksInterface $cartItemLinks
    */
    public function __construct(
        CartItemLinksInterface $cartItemLinks,
        \Magento\Quote\Api\GuestCartRepositoryInterface $cartItemRepository
     ) {
        $this->cartItemLinks = $cartItemLinks;
        $this->cartItemRepository = $cartItemRepository;
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
    private function loadData($cart) {

        foreach ($cart->getItems() as $cartItem) {
            $cartItemLinks = clone $this->cartItemLinks->load($cartItem);
            $cartItemExtensionAttributes = $cartItem->getExtensionAttributes();
            $cartItemExtensionAttributes->setLinks($cartItemLinks);
            $cartItem->setExtensionAttributes($cartItemExtensionAttributes);
        }

        return $cart;
    }
}