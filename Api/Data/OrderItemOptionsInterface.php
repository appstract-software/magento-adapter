<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

interface OrderItemOptionsInterface
{
  /**
   * Load data for order item.
   *
   * @return Appstractsoftware\MagentoAdapter\Api\Data\OrderItemOptionsInterface
   */
  public function load($label, $value);

  /**
   * Get option label
   * 
   * @return string
   */
  public function getOptionLabel();

  /**
   * Get option value
   * 
   * @return string
   */
  public function getOptionValue();

  /**
   * Set option label
   * 
   * @return string
   */
  public function setOptionLabel($min_price);

  /**
   * Set option value
   * 
   * @return string
   */
  public function setOptionValue($max_price);
}
