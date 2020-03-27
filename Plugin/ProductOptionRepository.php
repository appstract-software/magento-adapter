<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionInterface;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\Framework\Api\SearchResults;

class ProductOptionRepository
{
    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionInterface */
    private $productOptionLoader;
    
    /**
     * ProductRepository constructor.
    * 
    * @param ProductOptionInterface $productOptionLoader
    */
    public function __construct(ProductOptionInterface $productOptionLoader)
    {
        $this->productOptionLoader = $productOptionLoader;
    }

    /**
     * Add product options to extension attributes.
     * 
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface $product
     * @return ProductInterface
     */
    public function afterGetById(ProductRepositoryInterface $subject, ProductInterface $product)
    {
        return $this->loadData($product);
    }
    
    /**
     * Add product options to extension attributes.
     * 
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface $product
     * @return ProductInterface
     */
    public function afterGet(ProductRepositoryInterface $subject, ProductInterface $product)
    {
        return $this->loadData($product);
    }
    
    /**
     * Add product options to extension attributes.
     * 
     * @param ProductRepositoryInterface $subject
     * @param SearchResults $searchCriteria
     * @return SearchResults
     */
    public function afterGetList(ProductRepositoryInterface $subject, SearchResults $searchCriteria) : SearchResults
    {
        $products = [];
        foreach ($searchCriteria->getItems() as $product) {
            $products[] = $this->loadData($product);
        }
        $searchCriteria->setItems($products);
        return $searchCriteria;
    }

    /**
     * Load extension attribute data.
     *
     * @param ProductInterface $product
     * @return ProductInterface
     */
    private function loadData($product) {
        $productOptions = [];
        $typeInstance = $product->getTypeInstance(true);
        if (!empty($typeInstance) && method_exists($typeInstance, 'getConfigurableAttributesAsArray')) {
            $productAttributeOptions = $typeInstance->getConfigurableAttributesAsArray($product);
            foreach ($productAttributeOptions as $productAttribute) {
                $productOptions[] = clone $this->productOptionLoader->load($product, $productAttribute);
            }
            $extensionAttributes = $product->getExtensionAttributes();
            $extensionAttributes->setProductOptions($productOptions);
            $product->setExtensionAttributes($extensionAttributes);
        }

        return $product;
    }
}