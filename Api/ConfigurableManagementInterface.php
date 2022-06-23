<?php

namespace Appstractsoftware\MagentoAdapter\Api;

interface ConfigurableManagementInterface
{

  /**
   *  GET for parent Product by Child id
   * @param int $childId
   * @throws \Magento\Framework\Exception\LocalizedException
   * @return \Magento\Catalog\Api\Data\ProductInterface[]
   */
  public function getParentIdsByChild($childId);

  /**
   * GET for parent Product by Child SKU
   * @param string childSku
   * @throws \Magento\Framework\Exception\LocalizedException
   * @throws \Magento\Framework\Exception\NoSuchEntityException
   * @return \Magento\Catalog\Api\Data\ProductInterface[]
   */
  public function getParentIdsByChildSku($childSku);
}
