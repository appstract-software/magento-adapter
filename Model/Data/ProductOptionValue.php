<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValueInterface;

class ProductOptionValue implements ProductOptionValueInterface
{
    /** @var int|null */
    private $value_index;

    /** @var string|null */
    private $store_label;

    /**
     * @inheritDoc
     */
    public function load($attribute)
    {
        $this->value_index = $attribute['value_index'];
        $this->store_label = $attribute['store_label'];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getValueIndex()
    {
        return $this->value_index;
    }

    /**
     * @inheritDoc
     */
    public function getStoreLabel()
    {
        return $this->store_label;
    }

}