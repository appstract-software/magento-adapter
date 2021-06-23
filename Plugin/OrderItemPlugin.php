<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

class OrderItemPlugin
{

  /**
   * @var \Magento\Store\Model\StoreManagerInterface
   */
  private $storeManager;

  /**
   * @var \Magento\Catalog\Api\ProductRepositoryInterface
   */
  private $productRepository;

  /**
   * @var \Magento\Sales\Api\Data\OrderItemExtensionFactory
   */
  private $orderItemExtensionFactory;

  /** @var \Magento\Framework\EntityManager\Operation\Read\ReadExtensions $configurableProduct */
  private $configurableProduct;

  /** @var Appstractsoftware\MagentoAdapter\Api\Data\OrderItemOptionsInterface $orderItemOptions */
  private $orderItemOptions;

  public function __construct(
    \Magento\Sales\Api\Data\OrderItemExtensionFactory $orderItemExtensionFactory,
    \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProduct,
    \Appstractsoftware\MagentoAdapter\Api\Data\OrderItemOptionsInterface $orderItemOptions
  ) {

    $this->orderItemExtensionFactory = $orderItemExtensionFactory;
    $this->productRepository = $productRepository;
    $this->storeManager = $storeManager;
    $this->configurableProduct = $configurableProduct;
    $this->orderItemOptions = $orderItemOptions;
  }
  public function afterGetExtensionAttributes(
    \Magento\Sales\Api\Data\OrderItemInterface $subject,
    $result
  ) {
    $imageUrl = $this->getProductImage($subject);
    $options = $this->getProductOptions($subject);


    if (!$result) {
      $orderItemExtension = $this->orderItemExtensionFactory->create();

      if ($imageUrl) {
        $orderItemExtension->setProductImage($imageUrl);
      }

      if ($options) {
        $orderItemExtension->setOptions($options);
      }

      $subject->setExtensionAttributes($orderItemExtension);
    } else {
      $result->setProductImage($imageUrl);
      $result->setOptions($options);
    }

    return $result;
  }


  /**
   * Add cart options to extension attributes.
   * 
   * @return Appstractsoftware\MagentoAdapter\Api\Data\OrderItemOptionsInterface[]
   */
  private function getProductOptions($orderItem)
  {
    $productId = $orderItem->getProductId();
    $storeId = $orderItem->getStoreId();
    $productOptions = [];

    if (!$productId || !$storeId) {
      return null;
    }

    try {
      $product = $this->productRepository->getById($productId, false, $storeId);
      $productType = $product->getTypeId();
      if ($productType == \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE || $productType == \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL) {
        $parentIds = $this->configurableProduct->getParentIdsByChild($product->getId());
        if (!empty($parentIds)) {
          $parentId = array_shift($parentIds);
          $parent = $this->productRepository->getById($parentId, false, $storeId);

          $data = $parent->getTypeInstance()->getConfigurableOptions($parent);

          foreach ($data as $attributes) {
            foreach ($attributes as $prod) {
              if ($product->getSku() == $prod['sku']) {
                $productOptions[] = clone $this->orderItemOptions->load($prod['attribute_code'], $prod['option_title']);
              }
            }
          }
        }
      }
    } catch (\Magento\Framework\Exception\NoSuchEntityException $ex) {
    }

    return empty($productOptions) ? null : $productOptions;
  }


  /**
   * Add cart options to extension attributes.
   * 
   * @return string
   */
  private function getProductImage($orderItem)
  {

    $productId = $orderItem->getProductId();
    $storeId = $orderItem->getStoreId();

    if (!$productId || !$storeId) {
      return null;
    }

    try {
      $product = $this->productRepository->getById($productId);
      $imageUrl = $product->getImage();

      if (!$imageUrl) {
        return null;
      }

      // Zwracamy cały path wraz z hostem itd.
      return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
        . 'catalog/product' . $imageUrl;
    } catch (\Magento\Framework\Exception\NoSuchEntityException $ex) {
    }

    return null;
  }
}
