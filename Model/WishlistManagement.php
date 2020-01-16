<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use \Appstractsoftware\MagentoAdapter\Api\WishlistManagementInterface;
use \Appstractsoftware\MagentoAdapter\Api\WishlistRepositoryInterface;

use \Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\Catalog\Helper\ImageFactory as ProductImageHelper;
use \Magento\Framework\Exception\InputException;
use \Magento\Framework\Exception\NoSuchEntityException;
use \Magento\Store\Model\App\Emulation as AppEmulation;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Wishlist\Model\WishlistFactory;

class WishlistManagement implements WishlistManagementInterface
{
    /** @var \Magento\Catalog\Helper\ImageFactory => ProductImageHelper */
    protected $productImageHelper;
    /** @var \Magento\Catalog\Api\ProductRepositoryInterface */
    protected $productRepository;
    
   /** @var \Magento\Wishlist\Model\WishlistFactory */
   protected $wishlistFactory;

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
     * @param \Magento\Wishlist\Model\WishlistFactory $wishlistFactory
     * @param AppEmulation $appEmulation
     * @param StoreManagerInterface $storemanagerinterface
     */
    public function __construct(
        ProductImageHelper $productImageHelper,
        ProductRepositoryInterface $productRepository,
        \Magento\Wishlist\Model\WishlistFactory $wishlistFactory,
        AppEmulation $appEmulation,
        StoreManagerInterface $storemanagerinterface,
        WishlistRepositoryInterface $wishlistApiRepository
    ) {
        $this->productImageHelper = $productImageHelper;
        $this->productRepository = $productRepository;
        $this->wishlistFactory = $wishlistFactory;
        $this->appEmulation = $appEmulation;
        $this->storemanagerinterface = $storemanagerinterface;
        $this->wishlistApiRepository = $wishlistApiRepository;
    }

    /**
     * @inheritdoc
     */
    public function getWishlistBySharingCode($sharingCode)
    {
        $wishlist = $this->wishlistApiRepository->get($sharingCode);
        return $this->prepareWishlistDTO($wishlist);
    }

    /**
     * @inheritdoc
     */
    public function getWishlistById($id)
    {
        $wishlist = $this->wishlistApiRepository->getById($id);
        return $this->prepareWishlistDTO($wishlist);
    }

    /**
     * @inheritdoc
     */
    public function getWishlistByCustomerId($customerId)
    {
        $wishlist = $this->wishlistApiRepository->getById($customerId);

        $baseurl = $this->storemanagerinterface->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'catalog/product';
        $wishlist_data = [];
        $collection = $wishlist->getItemCollection();
        foreach ($collection as $item) {
            $productInfo = $item->getProduct()->toArray();
            if ($productInfo['small_image'] == 'no_selection') {
                $currentproduct = $this->productload->load($productInfo['entity_id']);
                $imageURL = $this->getImageUrl($currentproduct, 'product_base_image');
                $productInfo['small_image'] = $imageURL;
                $productInfo['thumbnail'] = $imageURL;
            } else {
                $imageURL = $baseurl.$productInfo['small_image'];
                $productInfo['small_image'] = $imageURL;
                $productInfo['thumbnail'] = $imageURL;
            }
            $data = [
                "wishlist_item_id" => $item->getWishlistItemId(),
                "wishlist_id"      => $item->getWishlistId(),
                "product_id"       => $item->getProductId(),
                "store_id"         => $item->getStoreId(),
                "added_at"         => $item->getAddedAt(),
                "description"      => $item->getDescription(),
                "qty"              => round($item->getQty()),
                "product"          => $productInfo
            ];
            $wishlistData[] = $data;
        }
        return $wishlistData;
    }

    /**
     * @inheritdoc
     */
    public function addProductToWishlistForCustomer($customerId, $productId) : bool
    {
        $product = $this->productRepository->getById($productId);
        $wishlist = $this->wishlistFactory->create()->loadByCustomerId($customerId, true);
        $wishlist->addNewItem($product);
        $returnData = $wishlist->save();

        return true;
    }


    /**
     * Helper function that provides full cache image url
     * @param \Magento\Catalog\Model\Product
     * @return string
     */
    public function getImageUrl($product, string $imageType = ''){
        $storeId = $this->storemanagerinterface->getStore()->getId();
        $this->appEmulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND, true);
        $imageUrl = $this->productImageHelper->create()->init($product, $imageType)->getUrl();
        $this->appEmulation->stopEnvironmentEmulation();

        return $imageUrl;
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
        $collection = $wishlist->getItemCollection();
        foreach ($collection as $item) {
            $productInfo = [];
            $data = [
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

        return [
            [
                'id' => $wishlist->getId(),
                'customer_id' => $wishlist->getCustomerId(),
                'shared' => $wishlist->getShared(),
                'sharing_code' => $wishlist->getSharingCode(),
                'items' => $items
            ]
        ];
    }
}