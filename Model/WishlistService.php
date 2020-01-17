<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use \Appstractsoftware\MagentoAdapter\Api\WishlistServiceInterface;
use \Appstractsoftware\MagentoAdapter\Api\WishlistRepositoryInterface;

use \Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\Catalog\Helper\ImageFactory as ProductImageHelper;
use \Magento\Framework\Exception\InputException;
use \Magento\Framework\Exception\NoSuchEntityException;
use \Magento\Store\Model\App\Emulation as AppEmulation;
use \Magento\Store\Model\StoreManagerInterface;

class WishlistService implements WishlistServiceInterface
{
    /** @var \Magento\Catalog\Helper\ImageFactory => ProductImageHelper */
    protected $productImageHelper;
    /** @var \Magento\Catalog\Api\ProductRepositoryInterface */
    protected $productRepository;
    
    /** @var \Magento\Store\Model\App\Emulation => AppEmulation*/
    protected $appEmulation;
    /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $storemanagerinterface;

    /** @var \Appstractsoftware\MagentoAdapter\Api\WishlistRepositoryInterface */
    protected $wishlistApiRepository;

    /**
     * Constructor.
     *
     * @param ProductImageHelper $productImageHelper
     * @param ProductRepositoryInterface $productRepository
     * @param AppEmulation $appEmulation
     * @param StoreManagerInterface $storemanagerinterface
     * @param WishlistRepositoryInterface $wishlistApiRepository
     */
    public function __construct(
        ProductImageHelper $productImageHelper,
        ProductRepositoryInterface $productRepository,
        AppEmulation $appEmulation,
        StoreManagerInterface $storemanagerinterface,
        WishlistRepositoryInterface $wishlistApiRepository
    ) {
        $this->productImageHelper = $productImageHelper;
        $this->productRepository = $productRepository;
        $this->appEmulation = $appEmulation;
        $this->storemanagerinterface = $storemanagerinterface;
        $this->wishlistApiRepository = $wishlistApiRepository;
    }

    /**
     * @inheritDoc
     */
    public function addProductToWishlistById($id, $productId): bool
    {
        // TODO: Implement method.
        return false;
    }

    /**
     * @inheritdoc
     */
    public function addProductToWishlistByCustomerId($customerId, $productId) : bool
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
    public function getWishlistById($id): array
    {
        $wishlist = $this->wishlistApiRepository->getById($id);
        return $this->prepareWishlistDTO($wishlist);
    }

    /**
     * @inheritdoc
     */
    public function getWishlistByCustomerId($customerId): array
    {
        $wishlist = $this->wishlistApiRepository->getById($customerId);
        return $this->prepareWishlistDTO($wishlist);
    }

    /**
     * @inheritdoc
     */
    public function getWishlistBySharingCode($sharingCode): array
    {
        $wishlist = $this->wishlistApiRepository->get($sharingCode);
        return $this->prepareWishlistDTO($wishlist);
    }


    /**
     * @inheritDoc
     */
    public function deleteWishlistById($id): bool
    {
        // TODO: Implement method.
        return false;
    }

    /**
     * @inheritDoc
     */
    public function deleteItemByItemIdFromWishlistById($id, $itemId): bool
    {
        // TODO: Implement method.
        return false;
    }

    /**
     * @inheritDoc
     */
    public function deleteItemByProductIdFromWishlistById($id, $productId): bool
    {
        // TODO: Implement method.
        return false;
    }

    /**
     * @inheritDoc
     */
    public function deleteItemByProductIdFromWishlistByCustomerId($customerId, $productId): bool
    {
        // TODO: Implement method.
        return false;
    }

    /**
     * @inheritDoc
     */
    public function deleteItemByItemIdFromWishlistByCustomerId($customerId, $itemId): bool
    {
        // TODO: Implement method.
        return false;
        return true;
    }


    /**
     * Returns DTO of wishlist from model wishlist.
     *
     * @param Magento\Wishlist\Model\Wishlist $wishlist
     * @return array
     */
    private function prepareWishlistDTO($wishlist): array
    {
        $items = [];
        $baseurl = $this->storemanagerinterface->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'catalog/product';
        $collection = $wishlist->getItemCollection();
        foreach ($collection as $item) {
            $productInfo = $item->getProduct()->toArray();
            if ($productInfo['small_image'] == 'no_selection') {
                $currentproduct = $this->productload->load($productInfo['entity_id']);
                $imageURL = $this->getImageUrl($currentproduct, 'product_base_image');
                $productInfo['small_image'] = $imageURL;
                $productInfo['thumbnail'] = $imageURL;
            } else {
                $imageURL = $baseurl . $productInfo['small_image'];
                $productInfo['small_image'] = $imageURL;
                $productInfo['thumbnail'] = $imageURL;
            }
            $data = [
                "id"               => $item->getWishlistItemId(),
                "wishlist_item_id" => $item->getWishlistItemId(),
                "wishlist_id"      => $item->getWishlistId(),
                "product_id"       => $item->getProductId(),
                "store_id"         => $item->getStoreId(),
                "added_at"         => $item->getAddedAt(),
                "description"      => $item->getDescription(),
                "qty"              => round($item->getQty()),
                "product"          => $productInfo
            ];
            $items[] = $data;
        }

        return [[
            'id'                => $wishlist->getId(),
            'wishlist_id'       => $wishlist->getId(),
            'customer_id'       => $wishlist->getCustomerId(),
            'sharing_code'      => $wishlist->getSharingCode(),
            'shared'            => $wishlist->getShared(),
            'name'              => $wishlist->getName(),
            'updated_at'        => $wishlist->getUpdatedAt(),
            'has_salable_items' => $wishlist->isSalable(),
            'items_count'       => $wishlist->getItemsCount(),
            'items'             => $items
        ]];
    }

    /**
     * Return full image url
     * 
     * @param \Magento\Catalog\Model\Product
     * @return string
     */
    private function getImageUrl($product, string $imageType = '')
    {
        $storeId = $this->storemanagerinterface->getStore()->getId();
        $this->appEmulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND, true);
        $imageUrl = $this->productImageHelper->create()->init($product, $imageType)->getUrl();
        $this->appEmulation->stopEnvironmentEmulation();

        return $imageUrl;
    }
}