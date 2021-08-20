<?php

namespace Appstractsoftware\MagentoAdapter\CustomGQL\Model\Resolver;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\ConfigurableProductGraphQl\Model\Options\Collection as OptionCollection;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Catalog\Model\Product\Type;
use Magento\Framework\GraphQl\Query\Resolver\ValueFactory;

/**
 * Class to resolve configurable options in simple product GraphQL query
 */
class ConfigurationOptionsResolver implements ResolverInterface
{
  /**
   * @var OptionCollection
   */
  private $optionCollection;

  /** 
   * @var Configurable
   */
  private $configurableProduct;

  /**
   * @var ValueFactory
   */
  private $valueFactory;

  public function __construct(
    OptionCollection $optionCollection,
    Configurable $configurableProduct,
    ValueFactory $valueFactory
  ) {
    $this->optionCollection = $optionCollection;
    $this->configurableProduct = $configurableProduct;
    $this->valueFactory = $valueFactory;
  }

  private function findOptionValue($values, $id)
  {
    foreach ($values as $value) {
      if ((int)$id == (int)$value['value_index']) {
        return $value['label'];
      }
    }

    return '';
  }

  /**
   *
   * {@inheritdoc}
   */
  public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
  {
    if (!array_key_exists('model', $value) || !$value['model'] instanceof ProductInterface) {
      throw new LocalizedException(__('"model" value should be specified'));
    }

    /* @var $product ProductInterface */
    $product = $value['model'];
    $productType = $product->getTypeId();

    if ($productType != Type::TYPE_SIMPLE && $productType != Type::TYPE_VIRTUAL) {
      return null;
    }

    $parentIds = $this->configurableProduct->getParentIdsByChild($product->getId());

    if (empty($parentIds)) {
      return null;
    }

    $parentId = array_shift($parentIds);

    $this->optionCollection->addProductId($parentId);

    $result = function () use ($parentId, $product) {
      $results = [];
      $options = $this->optionCollection->getAttributesByProductId($parentId);

      foreach ($options as $option) {
        $results[] = array(
          'code' => $option['attribute_code'],
          'label' => $option['label'],
          'value' => $this->findOptionValue($option['values'], $product->getData($option['attribute_code'])),
        );
      }

      return $results;
    };

    return $this->valueFactory->create($result);
  }
}
