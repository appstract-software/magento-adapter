<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\ProductLinksServiceInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductLinksInterface;

class ProductLinksService implements ProductLinksServiceInterface
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var Appstractsoftware\MagentoAdapter\Api\Data\ProductLinksInterface
     */
    protected $productLinksLoader;

    /**
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param Appstractsoftware\MagentoAdapter\Api\Data\ProductLinksInterface $productLinksLoader
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Appstractsoftware\MagentoAdapter\Api\Data\ProductLinksInterface $productLinksLoader
    ) {
        $this->productRepository = $productRepository;
        $this->productLinksLoader = $productLinksLoader;
    }

    /**
     * @inheritDoc
     */
    public function getLinks($sku)
    {
        $product = $this->productRepository->get($sku);
        return $this->productLinksLoader->load($product);
    }

    /**
     * @inheritDoc
     */
    public function getLinksByType($sku, $type)
    {
        $product = $this->productRepository->get($sku);
        return $this->productLinksLoader->loadWithType($product, $type);
    }
}
