<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Appstractsoftware\MagentoAdapter\Plugin\GuestCartItemRepository;

use \Magento\Quote\Api\Data\CartItemInterface;
use \Magento\Quote\Api\CartItemRepositoryInterface;
use \Magento\Quote\Model\Cart\CartItemRepository\Interceptor;
use \Magento\Framework\Api\SearchResults;
use \Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\Catalog\Helper\Image;

class CartItemRepository
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
     * Add cartItem options to extension attributes.
     * 
     * @param Magento\Quote\Model\Quote\Item\Repository\Interceptor $subject
     * @param SearchResults $searchCriteria
     * @return mixed
     */
    public function afterGetList(
        \Magento\Quote\Model\Quote\Item\Repository\Interceptor $subject,
        $items
     ) {
        foreach ($items as &$cartItem) {
            $this->guestCartItemRepository->loadData($cartItem);
        }
        return $items;
    }
}
