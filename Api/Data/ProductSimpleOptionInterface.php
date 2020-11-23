<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

use \Appstractsoftware\MagentoAdapter\Api\Data\ProductSimpleOptionProductsInterface;

interface ProductSimpleOptionInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * Load data for dto.
     *
     * @param mixed $attribute
     * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductSimpleOption
     */
    public function load($attribute, $product, $valueId);

    /**
     * @return string|null
     */
    public function getAttributeCode();

    /**
     * @return int|null
     */
    public function getAttributeId();

    /**
     * @return string|null
     */
    public function getValue();

    /**
     * @return number|null
     */
    public function getValueId();

    /**
     * @return bool|null
     */
    public function getIsSizeColor();

    /**
     * @return string|null
     */
    public function getAttributeLabel();

    /**
     * @return string|null
     */
    public function getAttributeFrontendLabel();

    /**
     * @return string|null
     */
    public function getAttributeStoreLabel();

    /**
     * @return void
     */
    public function setAttributeCode($attribute_code);

    /**
     * @return void
     */
    public function setAttributeId($attribute_id);

    /**
     * @return void
     */
    public function setValue($value);

    /**
     * @return void
     */
    public function setValueId($valueId);

    /**
     * @return void
     */
    public function setIsSizeColor($is_size_color);

    /**
     * @return void
     */
    public function setAttributeLabel($attribute_label);

    /**
     * @return void
     */
    public function setAttributeFrontendLabel($attribute_frontend_label);

    /**
     * @return void
     */
    public function setAttributeStoreLabel($attribute_store_label);
}
