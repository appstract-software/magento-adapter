<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValueInterface;

class ProductOption implements ProductOptionInterface
{
    /** @var int|null */
    private $id;

    /** @var string|null */
    private $attributeId;

    /** @var string|null */
    private $attributeCode;

    /** @var string|null */
    private $label;

    /** @var string|null */
    private $frontendLabel;

    /** @var string|null */
    private $storeLabel;

    /** @var int|null */
    private $position;

    /** @var bool|null */
    private $isUseDefault;

    /** @var int|null */
    private $productId;

    /** @var Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValueInterface[] */
    private $values;


    /** @var Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValueInterface productOptionValueLoader */
    private $productOptionValueLoader;

    /**
     * Constructor.
     *
     * @param ProductOptionValueInterface $productOptionValueLoader
     */
    public function __construct(ProductOptionValueInterface $productOptionValueLoader) {
        $this->productOptionValueLoader = $productOptionValueLoader;
    }

    /**
     * @inheritDoc
     */
    public function load($product, $productAttribute, $options, $data)
    {
        $this->values = [];
        foreach ($productAttribute['values'] as $attribute) {
            $this->values[] = clone $this->productOptionValueLoader->load($attribute, $productAttribute['attribute_code'], $options, $data);
        }

        $this->id             = $productAttribute['id'];
        $this->attributeId    = $productAttribute['attribute_id'];
        $this->attributeCode  = $productAttribute['attribute_code'];
        $this->position       = $productAttribute['position'];
        $this->isUseDefault   = $productAttribute['use_default'];
        $this->label          = $productAttribute['label'];
        $this->frontendLabel  = $productAttribute['frontend_label'];
        $this->storeLabel     = $productAttribute['store_label'];
        $this->productId      = $product->getId();

        return $this;
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
    public function getAttributeId()
    {
        return $this->attributeId;
    }

    /**
     * @inheritDoc
     */
    public function getAttributeCode()
    {
        return $this->attributeCode;
    }

    /**
     * @inheritDoc
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @inheritDoc
     */
    public function getFrontendLabel()
    {
        return $this->frontendLabel;
    }
    /**
     * @inheritDoc
     */
    public function getStoreLabel()
    {
        return $this->storeLabel;
    }

    /**
     * @inheritDoc
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @inheritDoc
     */
    public function getIsUseDefault()
    {
        return $this->isUseDefault;
    }

    /**
     * @inheritDoc
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @inheritDoc
     */
    public function getValues()
    {
        return $this->values;
    }
}