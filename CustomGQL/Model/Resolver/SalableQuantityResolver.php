<?php

namespace Appstractsoftware\MagentoAdapter\CustomGQL\Model\Resolver;

use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\InventoryCatalog\Model\GetStockIdForCurrentWebsite;
use Magento\ConfigurableProductGraphQl\Model\Variant\Collection;

/**
 * Class to resolve salable quantity in product GraphQL query
 */
class SalableQuantityResolver implements ResolverInterface
{

  /**
   * @var GetStockIdForCurrentWebsite
   */
  private $getStockIdForCurrentWebsite;

  /** 
   * @var GetProductSalableQtyInterface
   */
  private $getProductSalableQty;

  /**
   * @var Collection
   */
  private $variantCollection;

  public function __construct(
    GetProductSalableQtyInterface $getProductSalableQty,
    GetStockIdForCurrentWebsite $getStockIdForCurrentWebsite,
    Collection $variantCollection
  ) {
    $this->getProductSalableQty = $getProductSalableQty;
    $this->getStockIdForCurrentWebsite = $getStockIdForCurrentWebsite;
    $this->variantCollection = $variantCollection;
  }

  public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
  {
    if (!array_key_exists('model', $value) || !$value['model'] instanceof ProductInterface) {
      throw new LocalizedException(__('"model" value should be specified'));
    }

    /* @var $product ProductInterface */
    $product = $value['model'];
    $productType = $product->getTypeId();
    $stockId = $this->getStockIdForCurrentWebsite->execute();
    $qty = 0;

    if ($productType == 'configurable') {
      $this->variantCollection->addParentProduct($product);
      $children = $this->variantCollection->getChildProductsByParentId((int) $product->getId(), $context);

      foreach ($children as $key => $child) {
        $qty += $this->getProductSalableQty->execute($child['sku'], $stockId);
      }
    } else if ($productType == \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE || $productType == 'mpgiftcard') {
      $qty = $this->getProductSalableQty->execute($product->getSku(), $stockId);
    }

    return $qty;
  }
}
