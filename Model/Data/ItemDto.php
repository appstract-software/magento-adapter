<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ItemDtoInterface;

use Magento\Wishlist\Model\Item;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Catalog\Api\Data\ProductInterface;

class ItemDto implements ItemDtoInterface
{
    /** @var int $id */
    private $id;

    /** @var int $wishlist_id */
    private $wishlist_id;

    /** @var int $product_id */
    private $product_id;

    /** @var int $store_id */
    private $store_id;

    /** @var string $added_at */
    private $added_at;

    /** @var string $description */
    private $description;

    /** @var int $qty */
    private $qty;

    /** @var Magento\Catalog\Api\Data\ProductInterface */
    private $product;

    /**
     * @inheritDoc
     */
    public function load($item)
    {
        $this->id          = $item->getWishlistItemId();
        $this->wishlist_id = $item->getWishlistId();
        $this->product_id  = $item->getProductId();
        $this->store_id    = $item->getStoreId();
        $this->added_at    = $item->getAddedAt();
        $this->description = $item->getDescription();
        $this->qty         = round($item->getQty());
        $this->product     = $item->getProduct();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getWishlistId(): int
    {
        return $this->wishlist_id;
    }

    /**
     * @inheritDoc
     */
    public function getProductId(): int
    {
        return $this->product_id;
    }

    /**
     * @inheritDoc
     */
    public function getStoreId(): int
    {
        return $this->store_id;
    }

    /**
     * @inheritDoc
     */
    public function getAddedAt(): string
    {
        return $this->added_at;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return empty($this->description) ? "" : $this->description;
    }
    /**
     * @inheritDoc
     */

    public function getQty(): int
    {
        return $this->qty;
    }

    /**
     * @inheritDoc
     */
    public function getProduct()
    {
        return $this->product;
    }
}