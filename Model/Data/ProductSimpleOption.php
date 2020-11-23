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

    /** @var number|null */
    private $value_id;

    /** @var bool|null */
    private $is_size_color;

    /** @var string|null */
    private $attribute_label;

    /** @var string|null */
    private $attribute_frontend_label;

    /** @var string|null */
    private $attribute_store_label;

    /**
     * @inheritDoc
     */
    public function load($attribute, $product, $valueId)
    {
        $store_id = $product->getStoreId();
        $attribute->setStoreId($store_id);

        $labels = $attribute->getStoreLabels();
        $this->attribute_code           = $attribute->getAttributeCode();
        $this->attribute_id             = $attribute->getAttributeId();
        $this->attribute_label          = $attribute->getLabel();
        $this->attribute_frontend_label = $attribute->getFrontendLabel();
        $this->attribute_store_label    = empty($labels[$store_id]) ? $attribute->getStoreLabel() : $labels[$store_id];
        $this->value                    = $attribute->getFrontend()->getValue($product);
        $this->value_id                 = $valueId;
        $this->is_size_color            = in_array($attribute->getAttributeCode(), ['size', 'color']);

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
    public function getValueId()
    {
        return $this->value_id;
    }

    /**
     * @inheritDoc
     */
    public function getIsSizeColor()
    {
        return $this->is_size_color;
    }


    /**
     * @inheritDoc
     */
    public function getAttributeLabel()
    {
        return $this->attribute_label;
    }

    /**
     * @inheritDoc
     */
    public function getAttributeFrontendLabel()
    {
        return $this->attribute_frontend_label;
    }

    /**
     * @inheritDoc
     */
    public function getAttributeStoreLabel()
    {
        return $this->attribute_store_label;
    }

    /**
     * @inheritDoc
     */
    public function setAttributeCode($attribute_code)
    {
        $this->attribute_code = $attribute_code;
    }

    /**
     * @inheritDoc
     */
    public function setAttributeId($attribute_id)
    {
        $this->attribute_id = $attribute_id;
    }

    /**
     * @inheritDoc
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function setValueId($value_id)
    {
        $this->value_id = $value_id;
    }

    /**
     * @inheritDoc
     */
    public function setIsSizeColor($is_size_color)
    {
        $this->is_size_color = $is_size_color;
    }

    /**
     * @inheritDoc
     */
    public function setAttributeLabel($attribute_label)
    {
        $this->attribute_label = $attribute_label;
    }

    /**
     * @inheritDoc
     */
    public function setAttributeFrontendLabel($attribute_frontend_label)
    {
        $this->attribute_frontend_label = $attribute_frontend_label;
    }

    /**
     * @inheritDoc
     */
    public function setAttributeStoreLabel($attribute_store_label)
    {
        $this->attribute_store_label = $attribute_store_label;
    }
}
