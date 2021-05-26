<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductUnitsInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductConfigurationsInterface;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\Framework\Api\SearchResults;
use \Magento\InventorySalesApi\Api\Data\SalesChannelInterface;

class ProductItemRepository
{
    /** @var \Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface */
    private $productPriceLoader;

    /** @var \Magento\CatalogInventory\Api\StockRegistryInterface */
    private $stockRegistry;

    private $sourcesCache = array();

    /**
     * ProductRepository constructor.
     *
     * @param ProductPriceInterface $productPriceLoader
     */
    public function __construct(
        ProductPriceInterface $productPriceLoader,
        ProductUnitsInterface $productUnitsLoader,
        ProductConfigurationsInterface $productConfigurations,
        \Magento\CatalogInventory\Model\Spi\StockRegistryProviderInterface $stockRegistry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\InventorySalesApi\Api\GetProductSalableQtyInterface $getProductSalableQty,
        \Magento\InventorySalesApi\Api\IsProductSalableInterface $isProductSalable,
        \Magento\InventorySalesApi\Api\StockResolverInterface $stockResolver,
        \Magento\InventoryApi\Api\GetSourceItemsBySkuInterface $sourceItemsBySku,
        \Magento\InventoryApi\Api\SourceRepositoryInterface $sourceRepository,
        \Magento\Review\Model\ReviewFactory $reviewFactory
    ) {
        $this->productPriceLoader = $productPriceLoader;
        $this->productUnitsLoader = $productUnitsLoader;
        $this->stockRegistry = $stockRegistry;
        $this->storeManager = $storeManager;
        $this->getProductSalableQty = $getProductSalableQty;
        $this->stockResolver = $stockResolver;
        $this->isProductSalable = $isProductSalable;
        $this->sourceItemsBySku = $sourceItemsBySku;
        $this->sourceRepository = $sourceRepository;
        $this->productConfigurations = $productConfigurations;
        $this->reviewFactory = $reviewFactory;
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
        $stockId = $this->getStockId();

        return $this->loadData($subject, $product, $stockId);
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
        $stockId = $this->getStockId();

        return $this->loadData($subject, $product, $stockId);
    }

    /**
     * Add product options to extension attributes.
     *
     * @param ProductRepositoryInterface $subject
     * @param SearchResults $searchCriteria
     * @return SearchResults
     */
    public function afterGetList(ProductRepositoryInterface $subject, SearchResults $searchCriteria): SearchResults
    {
        $stockId = $this->getStockId();

        $products = [];
        foreach ($searchCriteria->getItems() as $product) {
            $products[] = $this->loadData($subject, $product, $stockId);
        }

        $searchCriteria->setItems($products);
        return $searchCriteria;
    }

    /**
     * Load extension attribute data.
     *
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface $product
     * @param int $stockId
     * @return ProductInterface
     */
    public function loadData($subject, $product, $stockId)
    {
        $typeInstance = $product->getTypeInstance();

        if (!empty($typeInstance)) {
            $extensionAttributes = $product->getExtensionAttributes();

            $productPrice = clone $this->productPriceLoader->load($product);
            $extensionAttributes->setProductPrice($productPrice);

            $units = clone $this->productUnitsLoader->load($product);
            $extensionAttributes->setUnits($units);

            $sources = array();
            $stockItem = $extensionAttributes->getStockItem();

            if ($stockItem == null) {
                $stockItem = $this->stockRegistry->getStockItem($product->getId(), null);
            }

            $stockItem->setStockId($stockId);
            $productType = $product->getTypeId();

            if ($productType == 'configurable') {
                $stockItems = array();
                $sourceItems = array();
                $configurations = array();

                $configurableProductStockStatus = false;
                $configurableProductStockQty = 0;

                $configurableProductsLinks = $extensionAttributes->getConfigurableProductLinks();

                foreach ($configurableProductsLinks as $link) {
                    $configurableProduct = $subject->getById($link);
                    $configurations[] = $this->productConfigurations->load($configurableProduct);

                    $configurableProductStockItem = $configurableProduct->getExtensionAttributes()->getStockItem();
                    $configurableProductSourceItems = $this->getSourceItems($configurableProduct->getSku());
                    $sourceItems = array_merge($sourceItems, $configurableProductSourceItems);

                    foreach ($sourceItems as $sourceItem) {
                        $sources[] = $this->getSource($sourceItem->getSourceCode());
                    }

                    $stockData = $this->getStockData($configurableProduct->getSku(), $stockId);

                    $configurableProductStockItem->setIsInStock($stockData['status']);
                    $configurableProductStockItem->setQty($stockData['qty']);

                    $stockItems[] = $configurableProductStockItem;

                    $configurableProductStockQty += $stockData['qty'];

                    if ($stockData['status'] == true) {
                        $configurableProductStockStatus = true;
                    }
                }

                $stockItem->setIsInStock($configurableProductStockStatus);
                $stockItem->setQty($configurableProductStockQty);

                $extensionAttributes->setStockItems($stockItems);
                $extensionAttributes->setSourceItems($sourceItems);
                $extensionAttributes->setConfigurations($configurations);
            } else if ($productType == \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
                $stockData = $this->getStockData($product->getSku(), $stockId);
                $stockItem->setIsInStock($stockData['status']);
                $stockItem->setQty($stockData['qty']);

                $sourceItems = $this->getSourceItems($product->getSku());
                $extensionAttributes->setSourceItems($sourceItems);

                foreach ($sourceItems as $sourceItem) {
                    $sources[] = $this->getSource($sourceItem->getSourceCode());
                }

                $extensionAttributes->setSources($sources);
            }

            $extensionAttributes->setStockItem($stockItem);
            $extensionAttributes->setSources(array_unique($sources, SORT_REGULAR));

            $this->reviewFactory->create()->getEntitySummary($product, $this->getStoreId());
            $ratingSummary = $product->getRatingSummary()->getRatingSummary();

            $extensionAttributes->setRatingSummary($ratingSummary);

            $product->setExtensionAttributes($extensionAttributes);
        }

        return $product;
    }

    private function getStockData($sku, $stockId)
    {
        $qty = $this->getProductSalableQty->execute($sku, $stockId);
        $status = $this->isProductSalable->execute($sku, $stockId);

        return array('qty' => $qty, 'status' => $status);
    }

    /**
     * Get stock id
     *
     * @return int
     */
    public function getStockId()
    {
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        $websiteCode = $this->storeManager->getWebsite($websiteId)->getCode();

        return $this->stockResolver->execute(SalesChannelInterface::TYPE_WEBSITE, $websiteCode)->getStockId();
    }

    /**
     * Get stock id
     *
     * @return int
     */
    private function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }

    /**
     * Get source items by given sku
     * 
     * @param string $sku
     * @return \Magento\InventoryApi\Api\Data\SourceItemInterface[]
     */
    private function getSourceItems($sku)
    {
        return $this->sourceItemsBySku->execute($sku);
    }

    /**
     * Get source by given source code
     * 
     * @param string $sourceCode
     * @return \Magento\InventoryApi\Api\Data\SourceInterface
     */
    private function getSource($sourceCode)
    {

        if (!array_key_exists($sourceCode, $this->sourcesCache)) {
            $source = $this->sourceRepository->get($sourceCode);
            $this->sourcesCache[$sourceCode] = $source;

            return $source;
        }

        return $this->sourcesCache[$sourceCode];
    }
}
