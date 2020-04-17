<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use \Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface;

use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\GuestCartItemRepositoryInterface;
use Magento\Quote\Model\GuestCart\GuestCartItemRepository\Interceptor;
use \Magento\Framework\Api\SearchResults;

class GuestCartItemRepository
{
    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface */
    private $cartItemLinks;
    
    /**
     * @var \Magento\Quote\Api\GuestCartItemRepositoryInterface
     */
    protected $cartItemRepository;

    /**
     * CartItemRepository constructor.
    * 
    * @param CartItemLinksInterface $cartItemLinks
    */
    public function __construct(
        CartItemLinksInterface $cartItemLinks,
        \Magento\Quote\Api\GuestCartItemRepositoryInterface $cartItemRepository
     ) {
        $this->cartItemLinks = $cartItemLinks;
        $this->cartItemRepository = $cartItemRepository;
    }

    /**
     * Add cartItem options to extension attributes.
     * 
     * @param Magento\Quote\Model\GuestCart\GuestCartItemRepository\Interceptor $subject
     * @param SearchResults $searchCriteria
     * @return mixed
     */
    public function afterGetList(
        \Magento\Quote\Model\GuestCart\GuestCartItemRepository\Interceptor $subject,
        $items
    )
    {
        $cartItems = [];
        foreach ($items as &$cartItem) {
            $cartItems = $this->loadData($cartItem);
        }
        return $items;
    }

    /**
     * Load extension attribute data.
     *
     * @param \Magento\Quote\Model\Quote\Item $cartItem
     * @return \Magento\Quote\Model\Quote\Item
     */
    private function loadData($cartItem) {

        $extensionAttributes = $cartItem->getExtensionAttributes();
        $extensionAttributes->setLinks($this->cartItemLinks->load($cartItem));
        $cartItem->setExtensionAttributes($extensionAttributes);

        return $cartItem;
    }
}