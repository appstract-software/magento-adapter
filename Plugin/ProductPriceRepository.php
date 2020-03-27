<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\Framework\Api\SearchResults;

class ProductPriceRepository
{
    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface */
    private $productPriceLoader;
    
    /**
     * ProductRepository constructor.
    * 
    * @param ProductPriceInterface $productPriceLoader
    */
    public function __construct(ProductPriceInterface $productPriceLoader)
    {
        $this->productPriceLoader = $productPriceLoader;
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
        $typeInstance = $product->getTypeInstance(true);
        if (!empty($typeInstance)) {
            $productPrice = clone $this->productPriceLoader->load($product);
            $extensionAttributes = $product->getExtensionAttributes();
            $extensionAttributes->setProductPrice($productPrice);
            $product->setExtensionAttributes($extensionAttributes);
        }

        return $product;
    }
}