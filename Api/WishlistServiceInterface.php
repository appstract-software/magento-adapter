<?php
namespace Appstractsoftware\MagentoAdapter\Api;
 
interface WishlistServiceInterface
{
    /**
     * Add product to wishlist by id
     *
     * @param int $id Wishlist id
     * @param int $productId Product id
     * @return bool
     */
    public function addProductToWishlistById($id, $productId): bool;

    /**
     * Add product to wishlist by customer id.
     *
     * @param int $customerId
     * @param int $productId
     * @return boolean
     */
    public function addProductToWishlistByCustomerId($customerId, $productId): bool;


    /**
     * Get Wishlist by id
     * 
     * @param int $id Wishlist id
     * @return array
     */
    public function getWishlistById($id): array;

    /**
     * Get Wishlist by customer id
     * 
     * @param int $customerId
     * @return array
     */
    public function getWishlistByCustomerId($customerId): array;

    /**
     * Get Wishlist by sharing code
     * 
     * @param string $sharingCode
     * @return array
     */
    public function getWishlistBySharingCode($sharingCode): array;


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
     * Delete item by product id from wishlist by id
     *
     * @param int $id
     * @param int $productId
     * @return bool
     */
    public function deleteItemByProductIdFromWishlistById($id, $productId): bool;

    /**
     * Delete item by product id from wishlist by customer id
     *
     * @param int $customerId
     * @param int $productId
     * @return bool
     */
    public function deleteItemByProductIdFromWishlistByCustomerId($customerId, $productId): bool;

    /**
     * Delete item by item id from wishlist by customer id
     *
     * @param int $customerId
     * @param int $itemId
     * @return bool
     */
    public function deleteItemByItemIdFromWishlistByCustomerId($customerId, $itemId): bool;
}