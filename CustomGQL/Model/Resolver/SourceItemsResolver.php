<?php

namespace Appstractsoftware\MagentoAdapter\CustomGQL\Model\Resolver;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

use Magento\ConfigurableProductGraphQl\Model\Variant\Collection;
use Magento\InventoryApi\Api\GetSourceItemsBySkuInterface;

/**
 * Class to resolve source items in product GraphQL query
 */
class SourceItemsResolver implements ResolverInterface
{
  /**
   * @var GetSourceItemsBySkuInterface
   */
  private $sourceItemsBySku;

  /**
   * @var Collection
   */
  private $variantCollection;

  public function __construct(
    GetSourceItemsBySkuInterface $sourceItemsBySku,
    Collection $variantCollection
  ) {
    $this->sourceItemsBySku = $sourceItemsBySku;
    $this->variantCollection = $variantCollection;
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


  public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
  {
    if (!array_key_exists('model', $value) || !$value['model'] instanceof ProductInterface) {
      throw new LocalizedException(__('"model" value should be specified'));
    }

    $product = $value['model'];
    $productType = $product->getTypeId();
    $results = [];

    if ($productType == 'configurable') {
      $this->variantCollection->addParentProduct($product);
      $children = $this->variantCollection->getChildProductsByParentId((int) $product->getId(), $context);

      foreach ($children as $key => $child) {
        $sourceItems = $this->getSourceItems($child['sku']);
        $results = array_merge($results, $sourceItems);
      }
    } else if ($productType == \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
      $results = $this->getSourceItems($product->getSku());
    }

    return $results;
  }
}
