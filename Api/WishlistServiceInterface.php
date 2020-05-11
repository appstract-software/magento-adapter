<?php
namespace Appstractsoftware\MagentoAdapter\Api;
 
use Appstractsoftware\MagentoAdapter\Api\Data\WishlistDtoInterface;

interface WishlistServiceInterface
{
    /**
     * Add product to wishlist by id
     *
     * @param int $id Wishlist id
     * @param int $productId Product id
     * @param string $sku Product sku
     * @return bool
     */
    public function addProductToWishlistById($id, $productId = null, $sku = null): bool;

    /**
     * Add product to wishlist by customer id.
     *
     * @param int $customerId
     * @param int $productId
     * @param string $sku
     * @return bool
     */
    public function addProductToWishlistByCustomerId($customerId, $productId = null, $sku = null): bool;


    /**
     * Get Wishlist by id
     * 
     * @param int $id Wishlist id
     * @return Appstractsoftware\MagentoAdapter\Api\Data\WishlistDtoInterface
     */
    public function getWishlistById($id);

    /**
     * Get Wishlist by customer id
     * 
     * @param int $customerId
     * @return Appstractsoftware\MagentoAdapter\Api\Data\WishlistDtoInterface
     */
    public function getWishlistByCustomerId($customerId);

    /**
     * Get Wishlist by sharing code
     * 
     * @param string $sharingCode
     * @return Appstractsoftware\MagentoAdapter\Api\Data\WishlistDtoInterface
     */
    public function getWishlistBySharingCode($sharingCode);


    /**
     * Delete wishlist by id
     *
     * @param int $id Wishlist id
     * @return bool
     */
    public function deleteWishlistById($id): bool;

    /**
     * Delete item by item id from wishlist by id
     *
     * @param int $id
     * @param int $itemId
     * @return bool
     */
    public function deleteItemByItemIdFromWishlistById($id, $itemId): bool;

    /**
     * Delete item by item id from wishlist by customer id
     *
     * @param int $customerId
     * @param int $itemId
     * @return bool
     */
    public function deleteItemByItemIdFromWishlistByCustomerId($customerId, $itemId): bool;
}