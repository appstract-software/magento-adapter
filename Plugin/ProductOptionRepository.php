<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface;
use \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\Framework\Api\SearchResults;

class ProductOptionRepository
{
    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface */
    private $productPriceLoader;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionInterface */
    private $productOptionLoader;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface */
    private $productImagesLoader;
    
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * ProductRepository constructor.
    * 
    * @param ProductOptionInterface $productOptionLoader
    */
    public function __construct(
        ProductOptionInterface $productOptionLoader,
        ProductPriceInterface $productPriceLoader,
        ProductImagesInterface $productImagesLoader,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     ) {
        $this->productOptionLoader = $productOptionLoader;
        $this->productPriceLoader = $productPriceLoader;
        $this->productImagesLoader = $productImagesLoader;
        $this->productRepository = $productRepository;
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
            $data = $product->getTypeInstance()->getConfigurableOptions($product);
            $options = [];
            foreach($data as $attributes) {
                foreach($attributes as $prod){
                    $options[$prod['sku']]['attributes'][$prod['attribute_code']] = [
                        'store_label' => $prod['option_title'],
                        'value_index' => $prod['value_index'],
                    ];
                    $options[$prod['sku']]['images'] = [];
                    $options[$prod['sku']]['price'] = [];
                }
            }

            foreach ($options as $sku => $p) {
                $productSimilar = $this->productRepository->get($sku);
                $options[$sku]['price'] = clone $this->productPriceLoader->load($productSimilar);
                $options[$sku]['images'] = [];
                foreach ($productSimilar->getMediaGalleryImages() as $image) {
                    $options[$sku]['images'][] = clone $this->productImagesLoader->load($image);
                }
            }

            $productAttributeOptions = $typeInstance->getConfigurableAttributesAsArray($product);
            foreach ($productAttributeOptions as $productAttribute) {
                $productOptions[] = clone $this->productOptionLoader->load($product, $productAttribute, $options, $data);
            }
            $extensionAttributes = $product->getExtensionAttributes();
            $extensionAttributes->setProductOptions($productOptions);
            $product->setExtensionAttributes($extensionAttributes);
        }

        return $product;
    }
}