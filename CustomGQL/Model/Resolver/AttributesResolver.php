<?php

namespace Appstractsoftware\MagentoAdapter\CustomGQL\Model\Resolver;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class to resolve custom attribute field in product GraphQL query
 */
class AttributesResolver implements ResolverInterface
{

  /** @var \Magento\Eav\Model\Config eavConfig */
  private $eavConfig;

  /** @var \Magento\Swatches\Helper\Data swatchHelper */
  private $swatchHelper;

  public function __construct(
    \Magento\Eav\Model\Config $eavConfig,
    \Magento\Swatches\Helper\Data $swatchHelper
  ) {
    $this->eavConfig = $eavConfig;
    $this->swatchHelper = $swatchHelper;
  }

  private function getSwatchValue(int $id)
  {
    $hashcodeData = $this->swatchHelper->getSwatchesByOptionsId([$id]);

    return $hashcodeData[$id]['value'];
  }


  public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
  {
    if (!array_key_exists('model', $value) || !$value['model'] instanceof ProductInterface) {
      throw new LocalizedException(__('"model" value should be specified'));
    }

    $selectedFields = [];

    foreach ($info->parentType->getFields() as $selectedField) {
      $selectedFields[] = $selectedField->name;
    }

    $attributes = [];
    $customAttributes = $value['model']->getCustomAttributes();

    foreach ($customAttributes as $customAttribute) {
      $customAttributeCode = $customAttribute->getAttributeCode();

      if (in_array($customAttributeCode, $selectedFields)) {
        $attributes[] = $customAttributeCode;
      }
    }

    $results = [];

    foreach ($attributes as $code) {
      $option = $value['model']->getData($code);

      if (!$option) {
        continue;
      }

      $eavAttribute = $this->eavConfig->getAttribute('catalog_product', $code);
      $type = $eavAttribute->getFrontendInput();

      $data = array(
        'code' => $code,
        'value' => $option,
        'type' => $type
      );

      if ($type == 'select' || $type == 'multiselect') {
        $isSwatch = $this->swatchHelper->isTextSwatch($eavAttribute);

        if (!$isSwatch) {
          $isSwatch = $this->swatchHelper->isVisualSwatch($eavAttribute);
        }

        $data['value'] = $eavAttribute->getSource()->getOptionText($option);

        if (is_array($data['value'])) {
          $data['value'] = implode(',', $data['value']);
        }

        if ($isSwatch) {
          $data['swatch'] = $this->getSwatchValue($option);
        }
      }

      $results[] = $data;
    }

    return $results;
  }
}
