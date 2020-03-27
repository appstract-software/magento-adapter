<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ProductPriceValueInterface;

use Magento\Directory\Model\Currency as CurrencyHelper;

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

    /**
     * Constructor.
     *
     * @param CurrencyHelper $currencyHelper
     */
    public function __construct(CurrencyHelper $currencyHelper) {
        $this->currencyHelper = $currencyHelper;
    }

    /**
     * @inheritDoc
     */
    public function load($product)
    {
        $this->price                = $product->getPrice();
        $this->specialPrice         = $product->getSpecialPrice();
        $this->currencyPrice        = $this->formatPrice($product->getPrice());
        $this->currencySpecialPrice = $this->specialPrice ? $this->formatPrice($this->specialPrice) : null;
        $this->currencySymbol       = $this->currencyHelper->getCurrencySymbol();

        return $this;
    }

    /**
     * Format price with currency.
     *
     * @param float $price
     * @return string
     */
    private function formatPrice($price)
    {
        return $this->currencyHelper->format($price, [], false);
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