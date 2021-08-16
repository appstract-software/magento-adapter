<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\ProductInterface;

class Product implements ProductInterface
{

  /**
   * @var SourceItemInterface
   */
  protected $sourceItemsSaveInterface;

  /**
   * @var \Magento\Catalog\Api\ProductRepositoryInterface
   */
  protected $productRepository;

  /**
   * @var \Magento\Framework\ObjectManagerInterface
   */
  protected $objectManager;

  /**
   * @var \Magento\InventoryApi\Api\Data\SourceItemInterfaceFactory
   */
  protected $sourceItem;

  public function __construct(
    \Magento\Framework\ObjectManagerInterface $objectManager,
    \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
    \Magento\InventoryApi\Api\Data\SourceItemInterfaceFactory $sourceItem,
    \Magento\InventoryApi\Api\SourceItemsSaveInterface $sourceItemsSaveInterface
  ) {
    $this->objectManager = $objectManager;
    $this->productRepository = $productRepository;
    $this->sourceItem = $sourceItem;
    $this->sourceItemsSaveInterface = $sourceItemsSaveInterface;
  }

  public function create($data)
  {
    try {
      $objectManager = $this->objectManager->getInstance();
      $configurableData = $data->getConfigurable();
      $simpleIds = [];
      $stocks = $data->getStocks();

      foreach ($data->getSimple() as $simpleData) {
        $sku = $simpleData->getSku();
        $product = $objectManager->create('\Magento\Catalog\Model\Product')
          ->setSku($simpleData->getSku())
          ->setName($simpleData->getName())
          ->setAttributeSetId($simpleData->getAttributeSetId())
          ->setStatus(1)
          ->setVisibility(1)
          ->setTypeId('simple')
          ->setWebsiteIds($data->getWebsiteIds())
          ->setPrice($simpleData->getPrice());

        if ($simpleData->getUrlKey()) {
          $product->setUrlKey($simpleData->getUrlKey());
        }

        if ($simpleData->getWeight()) {
          $product->setWeight($simpleData->getWeight());
        }

        foreach ($simpleData->getCustomAttributes() as $attribute) {
          $product->setCustomAttribute($attribute->getAttributeCode(), $attribute->getValue());
        }
        $product->setCategoryIds($data->getCategoryLinks());
        $simpleProduct = $product->save();
        array_push($simpleIds, $simpleProduct->getId());
      }

      $configurable = $objectManager->create('\Magento\Catalog\Model\Product')
        ->setSku($configurableData->getSku())
        ->setName($configurableData->getName())
        ->setAttributeSetId($configurableData->getAttributeSetId())
        ->setStatus(1)
        ->setWeight($configurableData->getWeight())
        ->setVisibility(4)
        ->setTypeId('configurable')
        ->setWebsiteIds($data->getWebsiteIds())
        ->setPrice($configurableData->getPrice())
        ->setStockData(
          [
            'use_config_manage_stock' => 0,
            'manage_stock' => 1,
            'is_in_stock' => 1,
          ]
        );

      if ($configurableData->getUrlKey()) {
        $configurable->setUrlKey($configurableData->getUrlKey());
      }

      if ($configurableData->getWeight()) {
        $configurable->setWeight($configurableData->getWeight());
      }

      foreach ($configurableData->getCustomAttributes() as $attribute) {
        $configurable->setCustomAttribute($attribute->getAttributeCode(), $attribute->getValue());
      }

      $configurable->setCategoryIds($data->getCategoryLinks());

      $size_attr_id = $configurable->getResource()->getAttribute($data->getConfigurableAttribute())->getId();
      $configurable->getTypeInstance()->setUsedProductAttributeIds(array($size_attr_id), $configurable);

      $configurableAttributesData = $configurable->getTypeInstance()->getConfigurableAttributesAsArray($configurable);
      $configurable->setCanSaveConfigurableAttributes(true);
      $configurable->setConfigurableAttributesData($configurableAttributesData);
      $configurableProductsData = array();
      $configurable->setConfigurableProductsData($configurableProductsData);

      try {
        $configurable->setAssociatedProductIds($simpleIds);
        $configurable->setCanSaveConfigurableAttributes(true);
        $configurable->save();


        foreach ($data->getSimple() as $simpleData) {
          $sku = $simpleData->getSku();
          $assignedStock = array_filter(
            $stocks,
            function ($e) use ($sku) {
              return $e->getSku() == $sku;
            }
          );


          $sourceItems = [];

          foreach ($assignedStock as $stock) {
            foreach ($stock->getSources() as $source) {
              $sourceItem = $this->sourceItem->create();
              $sourceItem->setSourceCode($source->getSource());
              $sourceItem->setQuantity($source->getQty());
              $sourceItem->setSku($sku);
              $sourceItem->setStatus($source->getQty() > 0 ? \Magento\InventoryApi\Api\Data\SourceItemInterface::STATUS_IN_STOCK : \Magento\InventoryApi\Api\Data\SourceItemInterface::STATUS_OUT_OF_STOCK);

              array_push($sourceItems, $sourceItem);
            }
          }

          if (!empty($sourceItems)) {
            $this->sourceItemsSaveInterface->execute($sourceItems);
          }
        }
      } catch (Exception $ex) {
        echo '<pre>';
        print_r($ex->getMessage());
        exit;
      }
    } catch (Exception $e) {
      echo "<pre>";
      print_r($e->getMessage());
      exit;
    }
  }

  public function setAttributes($sku, $attributes)
  {
    try {
      $product = $this->productRepository->get($sku);

      foreach ($attributes as $attribute) {
        $product->setCustomAttribute($attribute->getAttributeCode(), $attribute->getValue());
      }

      $product->save();
      return true;
    } catch (Exception $e) {
      return false;
    }
  }
}
