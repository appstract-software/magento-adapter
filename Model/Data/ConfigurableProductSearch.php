<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ConfigurableProductSearchInterface;

use \Magento\Catalog\Helper\Image;

class ConfigurableProductSearch implements ConfigurableProductSearchInterface
{
    /** @var string $sku */
    private $sku;

    /** @var int $id */
    private $id;

    /** @var string $name */
    private $name;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface $images */
    private $thumbnail;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface $price */
    private $price;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface $links */
    private $links;

    /**
     * @var Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface
     */
    protected $productPriceLoader;

    /**
     * @var Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface
     */
    protected $productImagesLoader;

    /**
     * @var Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface
     */
    protected $cartItemLinksLoader;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    const THUMBNAIL_WIDTH = '336';
    const THUMBNAIL_HEIGHT = '417';


    /**
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface $productPriceLoader
     * @param Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface $productImagesLoader
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface $productPriceLoader,
        \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface $productImagesLoader,
        \Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface $cartItemLinksLoader,
        \Magento\Catalog\Helper\Image $imageHelper
    ) {
        $this->productRepository = $productRepository;
        $this->productPriceLoader = $productPriceLoader;
        $this->productImagesLoader = $productImagesLoader;
        $this->cartItemLinksLoader = $cartItemLinksLoader;
        $this->imageHelper = $imageHelper;
    }

    /**
     * @inheritDoc
     */
    public function load($product)
    {
        $this->sku      = $product->getSku();
        $this->id       = $product->getId();
        $this->name     = $product->getName();
        $this->price    = clone $this->productPriceLoader->load($product);
        $this->links    = clone $this->cartItemLinksLoader->load($product);
        $this->thumbnail = null;

        if ($product->getThumbnail()) {
            try {
                $url = $this->imageHelper
                    ->init($product, 'thumbnail', ['type'=>'thumbnail'])
                    ->keepAspectRatio(true)
                    ->resize(self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT)
                    ->getUrl();
                $this->thumbnail = clone $this->productImagesLoader
                    ->load([
                        'url' => $url,
                        'width' => self::THUMBNAIL_WIDTH,
                        'height' => self::THUMBNAIL_HEIGHT,
                    ]);
            } catch (\Throwable $th) {}
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @inheritDoc
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @inheritDoc
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * @inheritDoc
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @inheritDoc
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @inheritDoc
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @inheritDoc
     */
    public function setLinks($links)
    {
        $this->links = $links;
    }
}