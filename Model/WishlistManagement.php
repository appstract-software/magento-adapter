<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use \Appstractsoftware\MagentoAdapter\Api\WishlistManagementInterface;

use \Magento\Wishlist\Model\WishlistFactory;
use \Magento\Framework\Exception\InputException;
use \Magento\Framework\Exception\NoSuchEntityException;
use \Magento\Catalog\Helper\ImageFactory as ProductImageHelper;
use \Magento\Store\Model\App\Emulation as AppEmulation;
use \Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory as WishlistCollectionFactory;
use \Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\Store\Model\StoreManagerInterface;

class WishlistManagement implements WishlistManagementInterface
{
    /** @var \Magento\Catalog\Helper\ImageFactory => ProductImageHelper */
    protected $productImageHelper;
    /** @var \Magento\Catalog\Api\ProductRepositoryInterface */
    protected $productRepository;
    
   /** @var \Magento\Wishlist\Model\WishlistFactory */
   protected $wishlistFactory;
   /** @var \Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory */
   protected $wishlistCollectionFactory;

     /** @var \Magento\Store\Model\App\Emulation => AppEmulation*/
    protected $appEmulation;
    /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $storemanagerinterface;

    /**
     * Constructor.
     *
     * @param ProductImageHelper $productImageHelper
     * @param ProductRepositoryInterface $productRepository
     * @param \Magento\Wishlist\Model\WishlistFactory $wishlistFactory
     * @param WishlistCollectionFactory $wishlistCollectionFactory
     * @param AppEmulation $appEmulation
     * @param StoreManagerInterface $storemanagerinterface
     */
    public function __construct(
        ProductImageHelper $productImageHelper,
        ProductRepositoryInterface $productRepository,
        \Magento\Wishlist\Model\WishlistFactory $wishlistFactory,
        WishlistCollectionFactory $wishlistCollectionFactory,
        AppEmulation $appEmulation,
        StoreManagerInterface $storemanagerinterface
    ) {
        $this->productImageHelper = $productImageHelper;
        $this->productRepository = $productRepository;
        $this->wishlistFactory = $wishlistFactory;
        $this->wishlistCollectionFactory = $wishlistCollectionFactory;
        $this->appEmulation = $appEmulation;
        $this->storemanagerinterface = $storemanagerinterface;
    }


    /**
     * @inheritdoc
     */
    public function getWishlistBySharingCode($sharingCode) {
        /** @var \Magento\Wishlist\Model\Wishlist */
        $wishlist = $this->wishlistFactory->create();
        $wishlist = $wishlist->loadByCode($sharingCode);
        if (empty($wishlist)) {
            throw new NoSuchEntityException(__('Wishlist with id "%1" does not exist.', $id));
        }
        return $wishlist;

    }

    /**
     * @inheritdoc
     */
    public function getWishlistById($id) {
        $wishlist = $this->wishlistFactory->create();
        $wishlist = $wishlist->loadByCustomerId($id);
        if (empty($wishlist)) {
            throw new NoSuchEntityException(__('Wishlist with id "%1" does not exist.', $id));
        }
        return $wishlist;
    }

    /**
     * @inheritdoc
     */
    public function getWishlistByCustomerId($customerId) {
        if (empty($customerId) || !isset($customerId) || $customerId == "") {
            throw new InputException(__('customerId is required'));
        }

        $baseurl = $this->storemanagerinterface->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'catalog/product';
        $collection = $this->wishlistCollectionFactory->create()->addCustomerIdFilter($customerId);
        $wishlistData = [];
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
}