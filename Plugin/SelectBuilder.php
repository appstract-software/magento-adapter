<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Magento\Framework\DB\Select;
use Magento\InventoryApi\Api\Data\SourceItemInterface;

/**
 * Select builder
 */
class SelectBuilder
{
  /**
   * @var \Appstractsoftware\MagentoAdapter\Helper\Data
   */
  private $helper;

  public function __construct(
    \Appstractsoftware\MagentoAdapter\Helper\Data $helper,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Framework\App\State $state
  ) {
    $this->helper = $helper;
    $this->storeManager = $storeManager;
    $this->state = $state;
  }

  /**
   * @return Select
   */
  public function afterExecute(\Magento\InventoryIndexer\Indexer\SelectBuilder $subject, Select $select): Select
  {
    $sourcesToSkip = $this->helper->getSourcesToSkip($this->storeManager->getStore()->getId());
    $area = $this->state->getAreaCode();

    if (count($sourcesToSkip) > 0 && $area != 'adminhtml') {
      $select->where('source_item.' . SourceItemInterface::SOURCE_CODE . ' NOT IN (?)', $sourcesToSkip);
    }

    return $select;
  }
}
