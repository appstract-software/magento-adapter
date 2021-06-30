<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Appstractsoftware\MagentoAdapter\Plugin;


class AttributeBuilder
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

    public function afterBuild($subject, array $result): array
    {
        foreach ($result as $key => $attribute) {
            $eavAttribute = $this->eavConfig->getAttribute('catalog_product', $attribute['attribute_code']);

            if (!$eavAttribute) {
                continue;
            }

            $type = $eavAttribute->getFrontendInput();
            $attribute['type'] = $type;

            if ($type == 'select') {
                $isSwatch = false;
                $isVisualSwatch = $this->swatchHelper->isVisualSwatch($eavAttribute);

                if ($isVisualSwatch) {
                    $attribute['type'] = 'swatch_visual';
                    $isSwatch = true;
                } elseif ($this->swatchHelper->isTextSwatch($eavAttribute)) {
                    $attribute['type'] = 'swatch_text';
                    $isSwatch = true;
                }

                if ($isSwatch) {
                    foreach ($attribute['options'] as $index => $option) {
                        $option['swatch_value'] = $this->getSwatchValue($option['value']);
                        $attribute['options'][$index] = $option;
                    }
                }
            }

            $result[$key] = $attribute;
        }

        return $result;
    }
}
