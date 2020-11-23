<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\CartItemInformationInterface;

use \Magento\Framework\Pricing\Helper\Data as CurrencyHelper;

class CartItemInformation implements CartItemInformationInterface
{
    /** @var string|null */
    private $store_code;

    /** @var string|null */
    private $store_id;

    /** @var string|null */
    private $currency_symbol;

    /** @var string|null */
    private $price_with_currency;

    /** @var string|null */
    private $category_flat_url;

    /** @var string|null */
    private $category_tree_url;

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
    public function load($product, $cartItem)
    {
        try {
          $this->store_code = $cartItem->getStore()->getCode();
          $this->store_id = $cartItem->getStore()->getId();
          $this->currency_symbol = $cartItem->getStore()->getBaseCurrency()->getCurrencySymbol();;
          $price = $cartItem->getPrice();
          $this->price_with_currency = $this->currencyHelper->currencyByStore($price, $cartItem->getStore()->getId(), true, false);

          $categories = $product->getCategoryIds();
          if (is_array($categories) && count($categories) > 0) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->category_flat_url = '';
            $this->category_tree_url = '';
            $isFirstCategory = true;
            foreach($categories as $category) {
              $cat = $objectManager->create('Magento\Catalog\Model\Category')->load($category);
              if ($isFirstCategory) {
                $this->category_flat_url = "/{$cat->getUrlKey()}";
                $isFirstCategory = false;
              }
              $this->category_tree_url .= "/{$cat->getUrlKey()}";
            }
          }
        } catch (\Throwable $th) {
        }

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function setStoreCode($store_code) {
      $this->store_code = $store_code;
    }

    /**
     * @inheritDoc
     */
    public function getStoreCode() {
      return $this->store_code;
    }

    /**
     * @inheritDoc
     */
    public function setStoreId($store_id) {
      $this->store_id = $store_id;
    }

    /**
     * @inheritDoc
     */
    public function getStoreId() {
      return $this->store_id;
    }

    /**
     * @inheritDoc
     */
    public function setCurrencySymbol($currency_symbol) {
      $this->currency_symbol = $currency_symbol;
    }

    /**
     * @inheritDoc
     */
    public function getCurrencySymbol() {
      return $this->currency_symbol;
    }

    /**
     * @inheritDoc
     */
    public function setPriceWithCurrency($price_with_currency) {
      $this->price_with_currency = $price_with_currency;
    }

    /**
     * @inheritDoc
     */
    public function getPriceWithCurrency() {
      return $this->price_with_currency;
    }

    /**
     * @inheritDoc
     */
    public function setCategoryFlatUrl($category_flat_url) {
      $this->category_flat_url = $category_flat_url;
    }

    /**
     * @inheritDoc
     */
    public function getCategoryFlatUrl() {
      return $this->category_flat_url;
    }

    /**
     * @inheritDoc
     */
    public function setCategoryTreeUrl($category_tree_url) {
      $this->category_tree_url = $category_tree_url;
    }

    /**
     * @inheritDoc
     */
    public function getCategoryTreeUrl() {
      return $this->category_tree_url;
    }
}
