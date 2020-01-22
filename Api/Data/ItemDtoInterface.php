<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

use Magento\Wishlist\Model\Item;
use Magento\Catalog\Api\Data\ProductInterface;

interface ItemDtoInterface
{
    /**
     * Load data for dto.
     *
     * @return Appstractsoftware\MagentoAdapter\Api\Data\ItemDtoInterface
     */
    public function load($item);

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Get wishlist id
     * 
     * @return int
     */
    public function getWishlistId(): int;

    /**
     * Get product id
     * 
     * @return int
     */
    public function getProductId(): int;

    /**
     * Get store id
     * 
     * @return int
     */
    public function getStoreId(): int;

    /**
     * Get added at
     * 
     * @return string
     */
    public function getAddedAt(): string;

    /**
     * Get description
     * 
     * @return string
     */
    public function getDescription(): string;

    /**
     * Get qty
     * 
     * @return int
     */
    public function getQty(): int;

    /**
     * Get product
     * 
     * @return Magento\Catalog\Api\Data\ProductInterface
     */
    public function getProduct();
}