<?php

namespace Appstractsoftware\MagentoAdapter\Model;

class ModuleLoader
{
  protected $moduleManager;
  protected $objectManager;

  public function __construct(
    \Magento\Framework\Module\Manager $moduleManager,
    \Magento\Framework\ObjectManagerInterface $objectManager
  ) {
    $this->moduleManager = $moduleManager;
    $this->objectManager = $objectManager;
  }

  public function create($module, $instanceName, array $data = array())
  {
    if ($this->moduleManager->isEnabled($module)) {
      return $this->objectManager->create($instanceName, $data);
    } else {
      return null;
    }
  }
}
