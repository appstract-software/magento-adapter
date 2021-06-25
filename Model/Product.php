<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\ProductInterface;

class Product implements ProductInterface
{

  /**
   * @var SourceItemInterface
   */
  protected $sourceItemsSaveInterface;

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
      var_dump('start');
      $objectManager = $this->objectManager->getInstance();
      $configurableData = $data->getConfigurable();
      $simpleIds = [];

      foreach ($data->getSimple() as $simpleData) {
        $product = $objectManager->create('\Magento\Catalog\Model\Product')
          ->setSku($simpleData->getSku())
          ->setName($simpleData->getName())
          ->setAttributeSetId($simpleData->getAttributeSetId())
          ->setStatus(1)
          ->setWeight($simpleData->getWeight())
          ->setVisibility(1)
          ->setTypeId('simple')
          ->setWebsiteIds($data->getWebsiteIds())
          ->setPrice($simpleData->getPrice());

        foreach ($simpleData->getCustomAttributes() as $attribute) {
          $product->setCustomAttribute($attribute->getAttributeCode(), $attribute->getValue());
        }
        $product->setCategoryIds($data->getCategoryLinks());
        $simpleProduct = $product->save();
        array_push($simpleIds, $simpleProduct->getId());


        $sourceItem = $this->sourceItem->create();
        $sourceItem->setSourceCode('111');
        $sourceItem->setQuantity(1);
        $sourceItem->setSku($product->getSku());
        $sourceItem->setStatus(\Magento\InventoryApi\Api\Data\SourceItemInterface::STATUS_IN_STOCK);

        $this->sourceItemsSaveInterface->execute([$sourceItem]);
      }

      var_dump('po simple');


      $configurable = $objectManager->create('\Magento\Catalog\Model\Product')
        ->setSku($configurableData->getSku())
        ->setName($configurableData->getName())
        ->setAttributeSetId($configurableData->getAttributeSetId())
        ->setStatus(1)
        ->setWeight($configurableData->getWeight())
        ->setVisibility(4)
        ->setTypeId('configurable')
        ->setWebsiteIds($data->getWebsiteIds())
        ->setPrice($configurableData->getPrice());

      foreach ($configurable->getCustomAttributes() as $attribute) {
        $configurable->setCustomAttribute($attribute->getAttributeCode(), $attribute->getValue());
      }

      $configurable->setCategoryIds($data->getCategoryLinks());

      $extensionAttrs = $configurable->getExtensionAttributes();
      $extensionAttrs->setConfigurableProductLinks($simpleIds);
      $size_attr_id = $configurable->getResource()->getAttribute('size_spodnie')->getId();
      $configurable->getTypeInstance()->setUsedProductAttributeIds(array($size_attr_id), $configurable);

      $configurableAttributesData = $configurable->getTypeInstance()->getConfigurableAttributesAsArray($configurable);
      $configurable->setCanSaveConfigurableAttributes(true);
      $configurable->setConfigurableAttributesData($configurableAttributesData);
      $configurableProductsData = array();
      $configurable->setConfigurableProductsData($configurableProductsData);
      try {
        $configurable->save();
      } catch (Exception $ex) {
        echo '<pre>';
        print_r($ex->getMessage());
        exit;
      }

      $productId = $configurable->getId();


      try {
        $configurable = $objectManager->create('Magento\Catalog\Model\Product')->load($productId); // Load Configurable Product
        $configurable->setAssociatedProductIds($simpleIds); // Setting Associated Products
        $configurable->setCanSaveConfigurableAttributes(true);
        $configurable->save();
      } catch (Exception $e) {
        echo "<pre>";
        print_r($e->getMessage());
        exit;
      }
    } catch (Exception $e) {
      echo "<pre>";
      print_r($e->getMessage());
      exit;
    }
  }

  public function getCustomAttribute($attributeCode)
  {
    $this->getCustomAttribute($attributeCode);
  }

  public function getCustomAttributes()
  {
    $this->getCustomAttributes();
  }

  public function setCustomAttribute($attributeCode, $attributeValue)
  {
    $this->getCustomAttributes($attributeCode, $attributeValue);
  }

  public function setCustomAttributes(\Magento\Framework\Api\AttributeInterface $attributes)
  {
    $this->getCustomAttributes($attributes);
  }
}
