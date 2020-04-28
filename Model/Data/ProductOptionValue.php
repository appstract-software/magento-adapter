<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValueInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValueProductsInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

class ProductOptionValue implements ProductOptionValueInterface
{
    /** @var int|null */
    private $value_index;

    /** @var string|null */
    private $store_label;
    
    /** @var ProductOptionValueProductsInterface[]|null */
    private $products;

    /** @var Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValueProductsInterface $productOptionValueProductsLoader */
    private $productOptionValueProductsLoader;

    /**
     * Constructor.
     *
     * @param Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionValueProductsInterface $productOptionValueLoader
     */
    public function __construct(ProductOptionValueProductsInterface $productOptionValueProductsLoader) {
        $this->productOptionValueProductsLoader = $productOptionValueProductsLoader;
    }

    /**
     * @inheritDoc
     */
    public function load($attribute, $attribute_code, $options, $data)
    {
        $this->value_index = $attribute['value_index'];
        $this->store_label = $attribute['store_label'];

        if (!empty($options) && !empty($data) && $attribute_code === 'color') {
            $this->products = [];
            foreach($data as $attributes){
                foreach($attributes as $prod){
                    if ($prod['attribute_code'] == $attribute_code && $prod['option_title'] == $this->store_label) {
                        $this->products[] = clone $this->productOptionValueProductsLoader->load($options, $prod);
                    }
                }
            }
        }

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
 
    /**
     * @inheritDoc
     */
    public function getProducts()
    {
        return $this->products;
    }
}