<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductUnitsInterface;

use \Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfig;
use \Magento\Store\Model\ScopeInterface;

class ProductUnits implements ProductUnitsInterface
{
    /** @var string */
    private $dimensionUnit;

    /** @var string */
    private $weightUnit;

    /** @var ScopeConfig */
    private $scopeConfig;

    /**
     * Constructor.
     *
     * @param ScopeConfig $ScopeConfig
     */
    public function __construct(ScopeConfig $scopeConfig) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritDoc
     */
    public function load($product)
    {
        $this->weightUnit = $this->scopeConfig->getValue(
          'general/locale/weight_unit',
          ScopeInterface::SCOPE_WEBSITE
        );
        $this->dimensionUnit = ($this->weightUnit === "kgs") ? "cm" : "in";

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDimensionUnit()
    {
        return $this->dimensionUnit;
    }

    /**
     * @inheritDoc
     */
    public function getWeightUnit()
    {
        return $this->weightUnit;
    }
}
