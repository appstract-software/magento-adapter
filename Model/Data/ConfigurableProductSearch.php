<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ConfigurableProductSearchInterface;

class ConfigurableProductSearch implements ConfigurableProductSearchInterface
{
    /** @var string $sku */
    private $sku;

    /** @var int $id */
    private $id;

    /** @var string $name */
    private $name;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface[] $images */
    private $images;

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
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface $productPriceLoader
     * @param Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface $productImagesLoader
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface $productPriceLoader,
        \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface $productImagesLoader,
        \Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface $cartItemLinksLoader
    ) {
        $this->productRepository = $productRepository;
        $this->productPriceLoader = $productPriceLoader;
        $this->productImagesLoader = $productImagesLoader;
        $this->cartItemLinksLoader = $cartItemLinksLoader;
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
        $this->images   = [];
        foreach ($product->getMediaGalleryImages() as $image) {
            $this->images[] = clone $this->productImagesLoader->load($image);
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
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @inheritDoc
     */
    public function setImages($images)
    {
        $this->images = $images;
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