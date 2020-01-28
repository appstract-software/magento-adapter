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
use Magento\Wishlist\Model\ItemFactory;

/**
 * WishlistService
 * 
 * @author Mateusz Lesiak <mateusz.lesiak@appstract.software>
 * @copyright 2020 Appstract Software
 */
class WishlistService implements WishlistServiceInterface
{
    /** @var \Magento\Catalog\Api\ProductRepositoryInterface */
    protected $productRepository;

    /** @var Appstractsoftware\MagentoAdapter\Api\WishlistRepositoryInterface */
    protected $wishlistApiRepository;

    /** @var Appstractsoftware\MagentoAdapter\Api\Data\WishlistDtoInterface */
    protected $wishlistDto;

    /** @var ItemResource */
    protected $itemResource;

    /** @var ItemFactory */
    protected $itemFactory;

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
        ItemFactory $itemFactory,
        ItemResource $itemResource
    ) {
        $this->productRepository = $productRepository;
        $this->wishlistApiRepository = $wishlistApiRepository;
        $this->wishlistDto = $wishlistDto;
        $this->itemFactory = $itemFactory;
        $this->itemResource = $itemResource;
    }

    /**
     * @inheritDoc
     */
    public function addProductToWishlistById($id, $productId): bool
    {
        $product = $this->productRepository->getById($productId);
        $wishlist = $this->wishlistApiRepository->getById($id);
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
    public function getWishlistById($id)
    {
        $wishlist = $this->wishlistApiRepository->getById($id);
        return $this->wishlistDto->load($wishlist);
    }

    /**
     * @inheritdoc
     */
    public function getWishlistByCustomerId($customerId)
    {
        $wishlist = $this->wishlistApiRepository->getByCustomerId($customerId);
        return $this->wishlistDto->load($wishlist);
    }

    /**
     * @inheritdoc
     */
    public function getWishlistBySharingCode($sharingCode)
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
        $item = $this->itemFactory->create()->load($itemId);
        if (!empty($item) && !empty($wishlist)) {
            if ($item->getWishlistId() == $wishlist->getId()) {
                $this->itemResource->delete($item);
            }
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteItemByItemIdFromWishlistByCustomerId($customerId, $itemId): bool
    {
        $wishlist = $this->wishlistApiRepository->getByCustomerId($customerId);
        $item = $this->itemFactory->create()->load($itemId);
        if (!empty($item) && !empty($wishlist)) {
            if ($item->getWishlistId() == $wishlist->getId()) {
                $this->itemResource->delete($item);
            }
        }
        return true;
    }
}