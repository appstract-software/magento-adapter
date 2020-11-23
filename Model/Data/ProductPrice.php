<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceValueInterface;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Store\Model\ScopeInterface;

use Magento\Framework\Pricing\Helper\Data as CurrencyHelper;

class ProductPrice implements ProductPriceInterface
{
    /** @var float|null */
    private $price;

    /** @var float|null */
    private $specialPrice;

    /** @var string|null */
    private $currencyPrice;

    /** @var string|null */
    private $currencySpecialPrice;

    /** @var string|null */
    private $currencySymbol;


    /** @var CurrencyHelper */
    private $currencyHelper;

    /** @var StoreManagerInterface */
    private $storeManager;

    /**
     * Constructor.
     *
     * @param CurrencyHelper $currencyHelper
     */
    public function __construct(CurrencyHelper $currencyHelper, StoreManagerInterface $storeManager) {
        $this->currencyHelper = $currencyHelper;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function load($product)
    {
        $price = $product->getPrice();
        if (empty($price) || $product->getTypeId() != "simple") {
            $price = $product->getFinalPrice();
        }
        $this->price                = $price;
        $this->specialPrice         = $product->getSpecialPrice();
        $store_id = $this->storeManager->getStore()->getId();
        $this->currencyPrice        = $this->formatPrice($price, $store_id);
        $this->currencySpecialPrice = $this->specialPrice ? $this->formatPrice($this->specialPrice, $store_id) : null;
        $this->currencySymbol       = $this->storeManager->getStore()->getBaseCurrency()->getCurrencySymbol();

        return $this;
    }

    /**
     * Format price with currency.
     *
     * @param float $price
     * @return string
     */
    private function formatPrice($price, $store_id)
    {
        return $this->currencyHelper->currencyByStore($price, $store_id, true, false);
    }

    /**
     * @inheritDoc
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @inheritDoc
     */
    public function getSpecialPrice()
    {
        return $this->specialPrice;
    }

    /**
     * @inheritDoc
     */
    public function getCurrencyPrice()
    {
        return $this->currencyPrice;
    }

    /**
     * @inheritDoc
     */
    public function getCurrencySpecialPrice()
    {
        return $this->currencySpecialPrice;
    }

    /**
     * @inheritDoc
     */
    public function getCurrencySymbol()
    {
        return $this->currencySymbol;
    }
}
