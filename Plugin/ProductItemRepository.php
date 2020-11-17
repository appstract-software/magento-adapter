<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductUnitsInterface;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\Framework\Api\SearchResults;

class ProductItemRepository
{
    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface */
    private $productPriceLoader;

    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductUnitsInterface */
    private $productUnits;

    /** @var \Magento\CatalogInventory\Api\StockRegistryInterface */
    private $stockRegistry;

    /**
     * ProductRepository constructor.
    *
    * @param ProductPriceInterface $productPriceLoader
    */
    public function __construct(
        ProductPriceInterface $productPriceLoader,
        ProductUnitsInterface $productUnitsLoader,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
    ) {
        $this->productPriceLoader = $productPriceLoader;
        $this->productUnitsLoader = $productUnitsLoader;
        $this->stockRegistry = $stockRegistry;
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
        if (!empty($products[0]) && $searchCriteria->getTotalCount() === 1) {
            $stockItem = $this->stockRegistry->getStockItem($products[0]->getId());
            $extensionAttributes = $products[0]->getExtensionAttributes();
            $extensionAttributes->setStockItem($stockItem);
            $products[0]->setExtensionAttributes($extensionAttributes);
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
            $extensionAttributes = $product->getExtensionAttributes();

            $productPrice = clone $this->productPriceLoader->load($product);
            $extensionAttributes->setProductPrice($productPrice);

            $units = clone $this->productUnitsLoader->load($product);
            $extensionAttributes->setUnits($units);

            $product->setExtensionAttributes($extensionAttributes);
        }

        return $product;
    }
}
