<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\WishlistRepositoryInterface;

use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use \Magento\Wishlist\Model\WishlistFactory;
use Magento\Wishlist\Model\ResourceModel\Wishlist as WishlistResource;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

class WishlistRepository implements WishlistRepositoryInterface
{
    /**
     * @var WishlistResource
     */
    private $wishlistResource;
    /**
     * @var WishlistFactory
     */
    private $wishlistFactory;


    /**
     * WishlistRepository constructor.
     * @param WishlistFactory $wishlistFactory
     * @param WishlistResource $wishlistResource
     */
    public function __construct(
        WishlistFactory $wishlistFactory,
        WishlistResource $wishlistResource
    ) {
        $this->wishlistResource = $wishlistResource;
        $this->wishlistFactory = $wishlistFactory;
    }

    /**
     * @inheritdoc
     */
    public function get($sharingCode)
    {
        $wishlist = $this->wishlistFactory->create();
        $this->wishlistResource->load($wishlist, $sharingCode, 'sharing_code');
        if (!$wishlist->getId()) {
            throw new NoSuchEntityException(__('Wishlist with sharing code "%1" does not exist.', $sharingCode));
        }
        return $wishlist->getDataModel();
    }

    /**
     * @inheritdoc
     */
    public function getById($id)
    {
        $wishlist = $this->wishlistFactory->create();
        $this->wishlistResource->load($wishlist, $id);
        if (!$id) {
            throw new NoSuchEntityException(__('Wishlist with id "%1" does not exist.', $id));
        }
        $wishlist_data = [];
        $collection = $wishlist->getItemCollection();
        foreach ($collection as $item) {
            $data = [
                "wishlist_item_id" => $item->getWishlistItemId(),
                "wishlist_id"      => $item->getWishlistId(),
                "product_id"       => $item->getProductId(),
                "store_id"         => $item->getStoreId(),
                "added_at"         => $item->getAddedAt(),
                "description"      => $item->getDescription(),
                "qty"              => round($item->getQty()),
            ];
            $wishlist_data[] = $data;
        }

        return $wishlist_data;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($id)
    {
        $wishlist = $this->get($id);
        try {
            $this->wishlistResource->delete($wishlist);
        } catch (\Exception $e) {
            throw new StateException(__('Cannot delete wishlist.'));
        }
        return true;
    }
}

