<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use \Magento\Framework\Api\SearchResults;
use Magento\Inventory\Model\SourceItem\Command\SourceItemsSave;

class SourceItem
{
  private $eventManager;

  public function __construct(\Magento\Framework\Event\ManagerInterface $eventManager)
  {
    $this->eventManager = $eventManager;
  }

  /**
   * Add cartItem options to extension attributes.
   * 
   * @param \Magento\Inventory\Model\SourceItem\Command\SourceItemsSave $subject
   * @param SearchResults $searchCriteria
   * @return mixed
   */
  public function beforeExecute($subject, $sourceItems)
  {
    $sourceCodes = [];
    $items = [];

    foreach ($sourceItems as $item) {
      if (!in_array($item->getSourceCode(), $sourceCodes) || $item->getSourceCode() != 'default' || $item->getSourceCode() != '0') {
        $sourceCodes[] = $item->getSourceCode();
        $items[] = $item;
      }
    }

    foreach ($items as $item) {
      $this->eventManager->dispatch('update_stock', ['sourceItem' => $item]);
    }
  }
}
