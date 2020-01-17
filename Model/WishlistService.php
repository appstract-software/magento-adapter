<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\WishlistServiceInterface;
use Appstractsoftware\MagentoAdapter\Api\WishlistRepositoryInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\WishlistDtoInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\WishlistDto;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Wishlist\Model\ResourceModel\Item as ItemResource;

class WishlistService implements WishlistServiceInterface
{
    /** @var \Magento\Catalog\Api\ProductRepositoryInterface */
    protected $productRepository;

    /** @var \Appstractsoftware\MagentoAdapter\Api\WishlistRepositoryInterface */
    protected $wishlistApiRepository;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\WishlistDtoInterface */
    protected $wishlistDto;

    /** @var ItemResource */
    protected $itemResource;

    /**
     * Constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     * @param WishlistRepositoryInterface $wishlistApiRepository
     * @param ItemResource $itemResource
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        WishlistRepositoryInterface $wishlistApiRepository,
        WishlistDtoInterface $wishlistDto,
        ItemResource $itemResource
    ) {
        $this->productRepository = $productRepository;
        $this->wishlistApiRepository = $wishlistApiRepository;
        $this->wishlistDto = $wishlistDto;
        $this->itemResource = $itemResource;
    }

    /**
     * @inheritDoc
     */
    public function addProductToWishlistById($id, $productId): bool
    {
        $product = $this->productRepository->getById($productId);
        $wishlist = $this->wishlistApiRepository->getById($customerId);
        $wishlist->addNewItem($product);
        $returnData = $wishlist->save();
        return true;
    }

    /**
     * @inheritdoc
     */
    public function addProductToWishlistByCustomerId($customerId, $productId) : bool
    {
        $product = $this->productRepository->getById($productId);
        $wishlist = $this->wishlistApiRepository->getByCustomerId($customerId);
        $wishlist->addNewItem($product);
        $returnData = $wishlist->save();
        return true;
    }


    /**
     * @inheritdoc
     */
    public function getWishlistById($id): WishlistDtoInterface
    {
        $wishlist = $this->wishlistApiRepository->getById($id);
        return $this->wishlistDto->load($wishlist);
    }

    /**
     * @inheritdoc
     */
    public function getWishlistByCustomerId($customerId): WishlistDtoInterface
    {
        $wishlist = $this->wishlistApiRepository->getByCustomerId($customerId);
        return $this->wishlistDto->load($wishlist);
    }

    /**
     * @inheritdoc
     */
    public function getWishlistBySharingCode($sharingCode): WishlistDtoInterface
    {
        $wishlist = $this->wishlistApiRepository->get($sharingCode);
        return $this->wishlistDto->load($wishlist);
    }


    /**
     * @inheritDoc
     */
    public function deleteWishlistById($id): bool
    {
        return $this->wishlistApiRepository->deleteById($id);
    }

    /**
     * @inheritDoc
     */
    public function deleteItemByItemIdFromWishlistById($id, $itemId): bool
    {
        $wishlist = $this->wishlistApiRepository->getById($id);
        $deleted = false;
        $item = $wishlist->getItem($itemId);
        if (!empty($item)) {
            $deleted = true;
            $this->itemResource->delete($item);
        }
        return $deleted;
    }

    /**
     * @inheritDoc
     */
    public function deleteItemByProductIdFromWishlistById($id, $productId): bool
    {
        $wishlist = $this->wishlistApiRepository->getById($id);
        $deleted = false;
        $items = $wishlist->getItemCollection();
        foreach($items as $item) {
            if ($item->getProductId() === $productId) {
                $deleted = true;
                $this->itemResource->delete($item);
            }
        }
        return $deleted;
    }

    /**
     * @inheritDoc
     */
    public function deleteItemByItemIdFromWishlistByCustomerId($customerId, $itemId): bool
    {
        $wishlist = $this->wishlistApiRepository->getByCustomerId($customerId);
        $deleted = false;
        $item = $wishlist->getItem($itemId);
        if (!empty($item)) {
            $deleted = true;
            $this->itemResource->delete($item);
        }
        return $deleted;
    }

    /**
     * @inheritDoc
     */
    public function deleteItemByProductIdFromWishlistByCustomerId($customerId, $productId): bool
    {
        $wishlist = $this->wishlistApiRepository->getByCustomerId($customerId);
        $deleted = false;
        $items = $wishlist->getItemCollection();
        foreach($items as $item) {
            if ($item->getProductId() === $productId) {
                $deleted = true;
                $this->itemResource->delete($item);
            }
        }
        return $deleted;
    }


    private function prepareWishlistDTO($wishlist)
    {
        foreach ($collection as $item) {
            
        }
    }

    
}