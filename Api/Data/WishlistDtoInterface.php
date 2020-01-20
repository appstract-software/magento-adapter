<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ItemDtoInterface;

use Magento\Wishlist\Model\Wishlist;

interface WishlistDtoInterface
{
    /**
     * Load data for dto.
     *
     * @return Appstractsoftware\MagentoAdapter\Api\Data\WishlistDtoInterface
     */
    public function load($wishlist): WishlistDtoInterface;

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Get customer id
     *
     * @return int
     */
    public function getCustomerId(): int;

    /**
     * Get sharing code
     *
     * @return string
     */
    public function getSharingCode(): string;

    /**
     * Get shared
     *
     * @return bool
     */
    public function getShared(): bool;

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * Get has salable items
     *
     * @return bool
     */
    public function getHasSalableItems(): bool;

    /**
     * Get items count
     *
     * @return int
     */
    public function getItemsCount(): int;

    /**
     * Get items
     * 
     * @return Appstractsoftware\MagentoAdapter\Api\Data\ItemDtoInterface[]
     */
    public function getItems();

}