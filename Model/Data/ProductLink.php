<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductLinkInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface;

use \Magento\Framework\Data\Collection;

class ProductLink implements ProductLinkInterface
{
    /** @var int $id */
    private $id;

    /** @var string $sku */
    private $sku;

    /** @var string $type */
    private $type;

    /** @var string $name */
    private $name;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface[] $images */
    private $images;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface $price */
    private $price;


    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface
     */
    protected $productPriceLoader;

    /**
     * @var Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface
     */
    protected $productImagesLoader;

    /**
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface $productPriceLoader
     * @param Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface $productImagesLoader
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface $productPriceLoader,
        \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface $productImagesLoader
    ) {
        $this->productRepository = $productRepository;
        $this->productPriceLoader = $productPriceLoader;
        $this->productImagesLoader = $productImagesLoader;
    }

    /**
     * @inheritDoc
     */
    public function load($productLink)
    {
        $product = $this->productRepository->get($productLink->getSku());

        $this->id     = $product->getId();
        $this->sku    = $product->getSku();
        $this->type   = $product->getTypeId();
        $this->name   = "" . $product->getName();
        $this->price  = clone $this->productPriceLoader->load($product);
        $this->images = [];
        foreach ($product->getMediaGalleryImages() as $image) {
            $this->images[] = clone $this->productImagesLoader->load($image);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
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
    public function getPrice()
    {
        return $this->price;
    }
}