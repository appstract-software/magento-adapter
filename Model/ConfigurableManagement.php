<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ConfigurableManagement implements \Appstractsoftware\MagentoAdapter\Api\ConfigurableManagementInterface
{
  /**
   * @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable
   */
  private $configurable;
  /**
   * @var \Magento\Catalog\Api\ProductRepositoryInterface
   */
  private $productRepository;

  public function __construct(
    Configurable $configurable,
    \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
  ) {
    $this->configurable = $configurable;
    $this->productRepository = $productRepository;
  }

  /**
   * {@inheritdoc}
   */
  public function getParentIdsByChild($childId)
  {
    $parentIds = $this->configurable->getParentIdsByChild($childId);

    $parentList = [];
    if (!empty($parentIds)) {
      foreach ($parentIds as $parentId) {
        $parentList[] = $this->productRepository->getById($parentId);
      }
    }

    return $parentList;
  }

  /**
   * {@inheritdoc}
   */
  public function getParentIdsByChildSku($childSku)
  {
    $child = $this->productRepository->get($childSku);

    if ($child->getTypeId() != \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
      return [];
    }
    return $this->getParentIdsByChild($child->getId());
  }
}
