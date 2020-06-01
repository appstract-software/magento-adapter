<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use \Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\CartItemQuantityInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\ProductSimpleOptionInterface;

use \Magento\Quote\Api\Data\CartItemInterface;
use \Magento\Quote\Api\GuestCartItemRepositoryInterface;
use \Magento\Quote\Model\GuestCart\GuestCartItemRepository\Interceptor;
use \Magento\Framework\Api\SearchResults;
use \Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\Catalog\Helper\Image;

class GuestCartItemRepository
{
    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface */
    private $cartItemLinks;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\CartItemQuantityInterface */
    private $cartItemQuantity;
    
    /**
     * @var \Magento\Quote\Api\GuestCartItemRepositoryInterface
     */
    protected $cartItemRepository;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface
     */
    protected $productImagesLoader;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionInterface */
    private $productSimpleOptionLoader;

    /**
     * CartItemRepository constructor.
    * 
    * @param CartItemLinksInterface $cartItemLinks
    * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    * @param Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface $productImagesLoader
    */
    public function __construct(
        CartItemLinksInterface $cartItemLinks,
        CartItemQuantityInterface $cartItemQuantity,
        \Magento\Quote\Api\GuestCartItemRepositoryInterface $cartItemRepository,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface $productImagesLoader,
        \Appstractsoftware\MagentoAdapter\Api\Data\ProductSimpleOptionInterface $productSimpleOptionLoader,
        \Magento\Catalog\Helper\Image $imageHelper
     ) {
        $this->cartItemLinks = $cartItemLinks;
        $this->cartItemQuantity = $cartItemQuantity;
        $this->cartItemRepository = $cartItemRepository;
        $this->productRepository = $productRepository;
        $this->productImagesLoader = $productImagesLoader;
        $this->productSimpleOptionLoader = $productSimpleOptionLoader;
        $this->imageHelper = $imageHelper;
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
     ) {
        foreach ($items as &$cartItem) {
            $this->loadData($cartItem);
        }
        return $items;
    }

    /**
     * Load extension attribute data.
     *
     * @param \Magento\Quote\Model\Quote\Item $cartItem
     * @return \Magento\Quote\Model\Quote\Item
     */
    public function loadData($cartItem) {
        $sku = '';
        if (is_array($cartItem)) {
            $sku = $cartItem['sku'];
        } else {
            $sku = $cartItem->getSku();
        }
        $product = $this->productRepository->get($sku);
        $extensionAttributes = $cartItem->getExtensionAttributes();

        $this->loadLinks($extensionAttributes, $product);

        $this->loadQuantity($extensionAttributes, $cartItem, $product);
        
        $this->loadProductOptions($extensionAttributes, $product);
        
        $this->loadImages($extensionAttributes, $product);

        $cartItem->setExtensionAttributes($extensionAttributes);

        return $cartItem;
    }

    /**
     * Returns product links.
     *
     * @param ProductInterface $product
     * @return void
     */
    private function loadLinks($extensionAttributes, $product) {
        try {
            $extensionAttributes->setLinks(clone $this->cartItemLinks->load($product));
        } catch (\Throwable $th) {}
    }

    /**
     * Returns product options.
     *
     * @param mixed $cartItem
     * @param ProductInterface $product
     * @return void
     */
    private function loadQuantity($extensionAttributes, $cartItem, $product) {
        try {
            $extensionAttributes->setQuantity(clone $this->cartItemQuantity->load($cartItem, $product));
        } catch (\Throwable $th) {}
    }
    
    /**
     * Returns product options.
     *
     * @param ProductInterface $product
     * @return void
     */
    private function loadProductOptions($extensionAttributes, $product) {
        try {
            $productOptions = [];
            $productAttributes = $product->getAttributes();
            foreach ($productAttributes as $attribute) {
                if ($attribute->getIsUserDefined()) {
                    $option = clone $this->productSimpleOptionLoader->load($attribute, $product);
                    if (!empty($option->getValue())) {
                        $productOptions[] = $option;
                    }
                }
            }
            $extensionAttributes->setProductOptions($productOptions);
        } catch (\Throwable $th) {}
    }
    
    const THUMBNAIL_WIDTH = '184';
    const THUMBNAIL_HEIGHT = '224';

    /**
     * Returns product images.
     *
     * @param ProductInterface $product
     * @return void
     */
    private function loadImages($extensionAttributes, $product) {
        try {
            if ($product->getThumbnail()) {
                $url = $this->imageHelper
                    ->init($product, 'thumbnail', ['type'=>'thumbnail'])
                    ->keepAspectRatio(true)
                    ->resize(self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT)
                    ->getUrl();
                $thumbnail = clone $this->productImagesLoader
                    ->load([
                        'url' => $url,
                        'width' => self::THUMBNAIL_WIDTH,
                        'height' => self::THUMBNAIL_HEIGHT,
                    ]);

                $extensionAttributes->setThumbnail($thumbnail);
            }
        } catch (\Throwable $th) {}
    }
}
