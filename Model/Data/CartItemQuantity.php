<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\CartItemQuantityInterface;

use \Magento\Catalog\Api\Data\ProductInterface;
use \Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class CartItemQuantity implements CartItemQuantityInterface
{
    /** @var string|null $sku */
    private $sku;

    /** @var int|null $qty */
    private $qty;
    
    /** @var int|null $productId */
    private $product_id;

    /** @var int|null $qty_available */
    private $qty_available;

    /**
     * @inheritDoc
     */
    public function load($cartItem, $product)
    {
        try {
            if (is_array($cartItem)) {
                $this->sku = $cartItem['sku'];
                $this->qty = $cartItem['qty'];
            } else {
                $this->sku = $cartItem->getSku();
                $this->qty = $cartItem->getQty();
            }
            $this->qty_available = $product->getExtensionAttributes()->getStockItem()->getQty();
            $this->product_id = $product->getId();
        } catch (\Throwable $th) {
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @inheritDoc
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @inheritDoc
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * @inheritDoc
     */
    public function getQtyAvailable()
    {
        return $this->qty_available;
    }

    /**
     * @inheritDoc
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @inheritDoc
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
    }

    /**
     * @inheritDoc
     */
    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    /**
     * @inheritDoc
     */
    public function setQtyAvailable($qty_available)
    {
        $this->qty_available = $qty_available;
    }
}