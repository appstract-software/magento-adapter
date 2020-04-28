<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductSimpleOptionInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductSimpleOptionProductsInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

class ProductSimpleOption implements ProductSimpleOptionInterface
{
    /** @var string|null */
    private $attribute_code;

    /** @var int|null */
    private $attribute_id;

    /** @var string|null */
    private $value;

    /** @var bool|null */
    private $is_size_color;

    /**
     * @inheritDoc
     */
    public function load($attribute, $product)
    {
        $this->attribute_code   = $attribute->getAttributeCode();
        $this->attribute_id     = $attribute->getAttributeId();
        $this->value            = $attribute->getFrontend()->getValue($product);
        $this->is_size_color    = in_array($attribute->getAttributeCode(), ['size', 'color']);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAttributeCode()
    {
        return $this->attribute_code;
    }

    /**
     * @inheritDoc
     */
    public function getAttributeId()
    {
        return $this->attribute_id;
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function getIsSizeColor()
    {
        return $this->is_size_color;
    }
}