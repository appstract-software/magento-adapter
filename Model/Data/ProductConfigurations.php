<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductConfigurationsInterface;

class ProductConfigurations implements ProductConfigurationsInterface
{
    /** @var int $id */
    private $id;

    /** @var string $sku */
    private $sku;

    /** @var \Magento\Framework\Api\AttributeInterface[]|null */
    private $attributes;

    /**
     * @inheritDoc
     */
    public function load($product)
    {
        $this->id = $product->getId();
        $this->sku = $product->getSku();
        $this->attributes = $product->getCustomAttributes();

        return clone $this;
    }
    /**
     * @inheritDoc
     */
    public function getAttributes()
    {
        return $this->attributes;
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
    public function getSku()
    {
        return $this->sku;
    }
}
