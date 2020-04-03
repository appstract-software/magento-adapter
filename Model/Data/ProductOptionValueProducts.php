<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValueProductsInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface;

class ProductOptionValueProducts implements ProductOptionValueProductsInterface
{
    /** @var string|null */
    private $sku;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface[] $images */
    private $images;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface $price */
    private $price;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValueInterface[] $productOptionValue */
    private $attributes;

    /**
     * @inheritDoc
     */
    public function load($options, $prod)
    {
        $this->sku = $prod['sku'];
        $this->attributes = $options[$prod['sku']]['attributes'];
        $this->price = $options[$prod['sku']]['price'];
        $this->images = $options[$prod['sku']]['images'];

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
    public function getPrice()
    {
        return $this->price;
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
    public function getAttributes()
    {
        return $this->attributes;
    }
}