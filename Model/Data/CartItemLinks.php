<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\CartItemLinksInterface;

use \Magento\Catalog\Api\Data\ProductInterface;
use \Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class CartItemLinks implements CartItemLinksInterface
{
    /** @var string|null $sku */
    private $sku;

    /** @var string|null $urlKey */
    private $urlKey;

    /** @var string|null $simpleUrlKey */
    private $simpleUrlKey;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable
     */
    protected $configurableProduct;

    /**
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProduct
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProduct
    ) {
        $this->productRepository = $productRepository;
        $this->configurableProduct = $configurableProduct;
    }

    /**
     * @inheritDoc
     */
    public function load($product)
    {
        try {
            $this->sku = $product->getSku();
            $this->urlKey = $product->getUrlKey();
            $this->simpleUrlKey = $product->getUrlKey();
            if ($product->getTypeId() == "simple") {
                $parentIds = $this->configurableProduct->getParentIdsByChild($product->getId());
                $parentId = array_shift($parentIds);
                $productParent = $this->productRepository->getById($parentId);
                $this->urlKey = $productParent->getUrlKey();
            }
        } catch (\Throwable $th) {
        }

        return $this;
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
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @inheritDoc
     */
    public function setUrlKey($urlKey)
    {
        $this->urlKey = $urlKey;
    }

    /**
     * @inheritDoc
     */
    public function getUrlKey()
    {
        return $this->urlKey;
    }

    /**
     * @inheritDoc
     */
    public function setSimpleUrlKey($simpleUrlKey)
    {
        $this->simpleUrlKey = $simpleUrlKey;
    }

    /**
     * @inheritDoc
     */
    public function getSimpleUrlKey()
    {
        return $this->simpleUrlKey;
    }
}