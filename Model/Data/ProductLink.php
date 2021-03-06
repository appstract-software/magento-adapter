<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductLinkInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface;

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

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface $links */
    private $links;


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
     * @var Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface
     */
    protected $cartItemLinksLoader;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    const THUMBNAIL_WIDTH = '128';
    const THUMBNAIL_HEIGHT = '152';
    

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
    public function load($productLink)
    {
        $product = $this->productRepository->get($productLink->getSku());

        $this->id     = $product->getId();
        $this->sku    = $product->getSku();
        $this->type   = $product->getTypeId();
        $this->name   = "" . $product->getName();
        $this->price  = clone $this->productPriceLoader->load($product);
        $this->links  = clone $this->cartItemLinksLoader->load($product);

        if ($product->getThumbnail()) {
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
        } else {
            $this->thumbnail = null;
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
    public function getLinks()
    {
        return $this->links;
    }
}