<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\WishlistServiceInterface;
use Appstractsoftware\MagentoAdapter\Api\WishlistRepositoryInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\WishlistDtoInterface;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Wishlist\Model\ResourceModel\Item as ItemResource;
use Magento\Wishlist\Model\ItemFactory;
use \Magento\ConfigurableProduct\Model\Product\Type\Configurable;

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

    /** @var Configurable */
    protected $configurableProduct;

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
        ItemResource $itemResource,
        Configurable $configurableProduct
    ) {
        $this->productRepository = $productRepository;
        $this->wishlistApiRepository = $wishlistApiRepository;
        $this->wishlistDto = $wishlistDto;
        $this->itemFactory = $itemFactory;
        $this->itemResource = $itemResource;
        $this->configurableProduct = $configurableProduct;
    }

    /**
     * @inheritDoc
     */
    public function addProductToWishlistById($id, $productId = null, $sku = null): bool
    {
        $product = $this->getProduct($productId, $sku);
        $wishlist = $this->wishlistApiRepository->getById($id);
        $wishlist->addNewItem($product);
        try {
            $wishlist->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function addProductToWishlistByCustomerId($customerId, $productId  = null, $sku= null): bool
    {
        $product = $this->getProduct($productId, $sku);
        $wishlist = $this->wishlistApiRepository->getByCustomerId($customerId);
        $wishlist->addNewItem($product);
        try {
            $wishlist->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    private function getProduct($productId, $sku)
    {
        if (empty($productId) && empty($sku)) {
            throw new \Magento\Framework\Webapi\Exception(__("Field 'product_id' and 'sku' cannot be empty together"));
        }
        $product = null;
        if(empty($productId)) {
            $product = $this->productRepository->get($sku);
        } else {
            $product = $this->productRepository->getById($productId);
        }
        if ($product->getTypeId() == "simple") {
            try {
                $parentIds = $this->configurableProduct->getParentIdsByChild($product->getId());
                $parentId = array_shift($parentIds);
                return $this->productRepository->getById($parentId);
            } catch (\Throwable $th) {
                throw new \Magento\Framework\Webapi\Exception(__("Cannot find configurable product connected with this simple product productId: '${productId}', sku: '${sku}'"));
            }
        }
        return $product;
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