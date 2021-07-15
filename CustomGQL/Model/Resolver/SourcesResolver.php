<?php

namespace Appstractsoftware\MagentoAdapter\CustomGQL\Model\Resolver;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

use Magento\ConfigurableProductGraphQl\Model\Variant\Collection;
use Magento\InventoryApi\Api\SourceRepositoryInterface;
use Magento\InventoryApi\Api\GetSourceItemsBySkuInterface;

/**
 * Class to resolve sources in product GraphQL query
 */
class SourcesResolver implements ResolverInterface
{
  /**
   * @var GetSourceItemsBySkuInterface
   */
  private $sourceItemsBySku;

  /**
   * @var SourceRepositoryInterface
   */
  private $sourceRepository;

  /**
   * @var Collection
   */
  private $variantCollection;

  public function __construct(
    GetSourceItemsBySkuInterface $sourceItemsBySku,
    SourceRepositoryInterface $sourceRepository,
    Collection $variantCollection
  ) {
    $this->sourceItemsBySku = $sourceItemsBySku;
    $this->sourceRepository = $sourceRepository;
    $this->variantCollection = $variantCollection;
  }

  /**
   * Get source by given source code
   * 
   * @param string $sourceCode
   * @return \Magento\InventoryApi\Api\Data\SourceInterface
   */
  private function getSource($sourceCode)
  {
    return $this->sourceRepository->get($sourceCode);
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
    $sourceItems = [];

    if ($productType == 'configurable') {
      $this->variantCollection->addParentProduct($product);
      $children = $this->variantCollection->getChildProductsByParentId((int) $product->getId(), $context);

      if (count($children) > 0) {
        $sourceItems = $this->getSourceItems($children[0]['sku']);
      }
    } else if ($productType == \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
      $sourceItems = $this->getSourceItems($product->getSku());
    }

    foreach ($sourceItems as $sourceItem) {
      $results[] = $this->getSource($sourceItem->getSourceCode());
    }

    return $results;
  }
}
