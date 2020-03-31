<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductLinksInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductLinkInterface;

class ProductLinks implements ProductLinksInterface
{
    /** @var Appstractsoftware\MagentoAdapter\Api\Data\ProductLinkInterface[] $related */
    private $related;
    
    /** @var Appstractsoftware\MagentoAdapter\Api\Data\ProductLinkInterface[] $crosssell */
    private $crosssell;
    
    /** @var Appstractsoftware\MagentoAdapter\Api\Data\ProductLinkInterface[] $upsell */
    private $upsell;


    /**
     * @var Appstractsoftware\MagentoAdapter\Api\Data\ProductLinkInterface
     */
    protected $productLinkLoader;

    /**
     * @param Appstractsoftware\MagentoAdapter\Api\Data\ProductLinkInterface $productLinkLoader
     */
    public function __construct(
        \Appstractsoftware\MagentoAdapter\Api\Data\ProductLinkInterface $productLinkLoader
    ) {
        $this->productLinkLoader = $productLinkLoader;
    }

    /**
     * @inheritDoc
     */
    public function load($product)
    {
        $this->related   = $this->loadWithType($product, 'related');
        $this->crosssell = $this->loadWithType($product, 'crosssell');
        $this->upsell    = $this->loadWithType($product, 'upsell');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function loadWithType($product, $type)
    {
        $productLinks = [];
        switch ($type) {
            case 'related':
                $productLinks = $product->getRelatedProducts();
            break;
            case 'crosssell':
                $productLinks = $product->getCrossSellProducts();
            break;
            case 'upsell':
                $productLinks = $product->getUpSellProductIds();
            break;
        }

        $links = [];
        foreach ($productLinks as $productLink) {
            $links[]  = clone $this->productLinkLoader->load($productLink);
        }
        return $links;
    }

    /**
     * @inheritDoc
     */
    public function getRelated()
    {
        return $this->related;
    }

    /**
     * @inheritDoc
     */
    public function getCrosssell()
    {
        return $this->crosssell;
    }

    /**
     * @inheritDoc
     */
    public function getUpsell()
    {
        return $this->upsell;
    }
}