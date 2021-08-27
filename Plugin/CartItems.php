<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class CartItems
{
  /** 
   * @var Configurable
   */
  private $configurableProduct;

  public function __construct(
    Configurable $configurableProduct,
    ProductRepositoryInterface $productRepository,
    SearchCriteriaBuilder $searchCriteriaBuilder
  ) {
    $this->configurableProduct = $configurableProduct;
    $this->productRepository = $productRepository;
    $this->searchCriteriaBuilder = $searchCriteriaBuilder;
  }

  public function afterResolve($subject, array $itemsData, Field $field, $context, ResolveInfo $info)
  {
    $parentFieldExists = false;

    foreach ($info->fieldNodes as $node) {
      foreach ($node->selectionSet->selections as $selection) {
        if ($selection->name->value == 'parent') {
          $parentFieldExists = true;
          break;
        }
      }
    }

    if (!$parentFieldExists) {
      return $itemsData;
    }

    $ids = [];
    $parentChild = [];

    foreach ($itemsData as $item) {
      $productType = $item['product']['type_id'];
      $productId = $item['product']['entity_id'];

      if ($productType === Type::TYPE_SIMPLE || $productType === Type::TYPE_VIRTUAL) {
        $parentIds = $this->configurableProduct->getParentIdsByChild($productId);

        if (empty($parentIds)) {
          continue;
        }

        $parentId = array_shift($parentIds);
        $ids[] = $parentId;
        $parentChild[$parentId] = $productId;
      }
    }

    if (empty($ids)) {
      return $itemsData;
    }

    $searchCriteria = $this->searchCriteriaBuilder->addFilter('entity_id', $ids, 'in')->create();
    $products = $this->productRepository->getList($searchCriteria)->getItems();

    $parentsData = [];

    foreach ($products as $parent) {
      $parentsData[$parentChild[$parent->getId()]] = $parent;
    }

    foreach ($itemsData as $key => $item) {
      if (isset($parentsData[$item['product']['entity_id']])) {
        $itemsData[$key]['parent'] = $parentsData[$item['product']['entity_id']];
      }
    }

    return $itemsData;
  }
}
