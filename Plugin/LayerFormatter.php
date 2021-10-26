<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

/**
 * Format Layered Navigation Items
 */
class LayerFormatter
{
  /**
   * Format layer data
   *
   * @param string $layerName
   * @param string $itemsCount
   * @param string $requestName
   * @param int $position
   * @return array
   */
  public function aroundBuildLayer($subject, callable $proceed, $layerName, $itemsCount, $requestName, $position = null): array
  {
    return [
      'label' => $layerName,
      'count' => $itemsCount,
      'attribute_code' => $requestName,
      'position' => isset($position) ? (int)$position : null
    ];
  }
}
