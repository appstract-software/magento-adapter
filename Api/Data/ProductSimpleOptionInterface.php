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
    public function load($attribute, $product);

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
     * @return bool|null
     */
    public function getIsSizeColor();

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
    public function setIsSizeColor($is_size_color);
}