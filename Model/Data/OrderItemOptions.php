<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\OrderItemOptionsInterface;

class OrderItemOptions implements OrderItemOptionsInterface
{
  /** @var string $label */
  private $label;

  /** @var string $value */
  private $value;

  /**
   * @inheritDoc
   */
  public function load($label, $value)
  {
    $this->label  = $label;
    $this->value  = $value;

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getOptionLabel()
  {
    return $this->label;
  }

  /**
   * @inheritDoc
   */
  public function getOptionValue()
  {
    return $this->value;
  }

  /**
   * @inheritDoc
   */
  public function setOptionLabel($label)
  {
    $this->label = $label;
  }

  /**
   * @inheritDoc
   */
  public function setOptionValue($value)
  {
    $this->value = $value;
  }
}
