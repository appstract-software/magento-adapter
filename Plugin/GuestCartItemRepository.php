<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use \Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\CartItemQuantityInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\ProductSimpleOptionInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\CartItemInformationInterface;

use \Magento\Quote\Api\Data\CartItemInterface;
use \Magento\Quote\Api\GuestCartItemRepositoryInterface;
use \Magento\Quote\Model\GuestCart\GuestCartItemRepository\Interceptor;
use \Magento\Framework\Api\SearchResults;
use \Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\Catalog\Helper\Image;
use \Magento\Framework\Serialize\Serializer\Json;
use \Magento\Framework\App\ObjectManager;

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

    /**
     * @var Appstractsoftware\MagentoAdapter\Api\Data\CartItemInformationInterface
     */
    protected $cartItemInformations;

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
        \Appstractsoftware\MagentoAdapter\Api\Data\CartItemInformationInterface $cartItemInformations,
        \Magento\Catalog\Helper\Image $imageHelper,
        Json $serializer = null
     ) {
        $this->cartItemLinks = $cartItemLinks;
        $this->cartItemQuantity = $cartItemQuantity;
        $this->cartItemRepository = $cartItemRepository;
        $this->productRepository = $productRepository;
        $this->productImagesLoader = $productImagesLoader;
        $this->productSimpleOptionLoader = $productSimpleOptionLoader;
        $this->imageHelper = $imageHelper;
        $this->cartItemInformations = $cartItemInformations;
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
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
        $product->setStoreId($cartItem->getStore()->getId());
        $extensionAttributes = $cartItem->getExtensionAttributes();

        $this->loadLinks($extensionAttributes, $product, $cartItem);
        $this->loadQuantity($extensionAttributes, $product, $cartItem);
        $this->loadProductOptions($extensionAttributes, $product, $cartItem);
        $this->loadImages($extensionAttributes, $product, $cartItem);
        $this->loadInformations($extensionAttributes, $product, $cartItem);

        $cartItem->setExtensionAttributes($extensionAttributes);

        return $cartItem;
    }

    /**
     * Returns product links.
     *
     * @param ProductInterface $product
     * @return void
     */
    private function loadLinks($extensionAttributes, $product, $cartItem) {
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
    private function loadQuantity($extensionAttributes, $product, $cartItem) {
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
    private function loadProductOptions($extensionAttributes, $product, $cartItem) {
        try {
            $attributesOption = $cartItem->getProduct()->getCustomOption('attributes');
            $selectedConfigurableOptions = $this->serializer->unserialize($attributesOption->getValue());
            $configurableOptions = [];
            if (is_array($selectedConfigurableOptions)) {
                foreach ($selectedConfigurableOptions as $optionId => $optionValue) {
                    $configurableOptions[$optionId] = $optionValue;
                }
            }

            $productOptions = [];
            $attributesOption = $cartItem->getProduct()->getCustomOption('attributes');
            $productAttributes = $product->getAttributes();
            foreach ($productAttributes as $attribute) {
                $attributeId = intval($attribute->getAttributeId());
                if ($attribute->getIsUserDefined() && array_key_exists($attributeId, $configurableOptions)) {
                    $valueId = empty($configurableOptions[$attributeId]) ? -1 : $configurableOptions[$attributeId];
                    $option = clone $this->productSimpleOptionLoader->load($attribute, $product, $valueId);
                    if (!empty($option->getValue())) {
                        $productOptions[] = $option;
                    }
                }
            }
            $extensionAttributes->setProductOptions($productOptions);
        } catch (\Throwable $th) {
        }
    }

    const THUMBNAIL_WIDTH = '184';
    const THUMBNAIL_HEIGHT = '224';

    /**
     * Returns product images.
     *
     * @param ProductInterface $product
     * @return void
     */
    private function loadImages($extensionAttributes, $product, $cartItem) {
        try {
            if ($product->getThumbnail()) {
                $image = $this->imageHelper
                    ->init($product, 'thumbnail', ['type'=>'thumbnail'])
                    ->keepAspectRatio(true)
                    ->resize(self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT);
                $imageInfo = empty($image->getResizedImageInfo()) ? [] : $image->getResizedImageInfo();
                $mime = empty($imageInfo['mime']) ? null : $imageInfo['mime'];
                $thumbnail = clone $this->productImagesLoader
                    ->load([
                        'url' => $image->getUrl(),
                        'media_type' => 'image',
                        'mime' => $mime,
                        'width' => self::THUMBNAIL_WIDTH,
                        'height' => self::THUMBNAIL_HEIGHT,
                    ]);

                $extensionAttributes->setThumbnail($thumbnail);
            }
        } catch (\Throwable $th) {}
    }

    /**
     * Returns more informations about cart item.
     *
     * @param ProductInterface $product
     * @return void
     */
    private function loadInformations($extensionAttributes, $product, $cartItem) {
        try {
            $informations = clone $this->cartItemInformations->load($product, $cartItem);
            $extensionAttributes->setInformations($informations);
        } catch (\Throwable $th) {}
    }
}
