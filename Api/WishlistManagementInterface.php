<?php
namespace Appstractsoftware\MagentoAdapter\Api;
 
interface WishlistManagementInterface
{
    /**
     * Get Wishlist by sharing code
     * 
     * @param string $sharingCode
     * @return array
     */
    public function getWishlistBySharingCode($sharingCode);

    /**
     * Get Wishlist by id
     * 
     * @param int $id
     * @return array
     */
    public function getWishlistById($id);

    /**
     * Get Wishlist by customer id
     * 
     * @param int $id
     * @return array
     */
    public function getWishlistByCustomerId($customerId);

    /**
     * Add product to wishlist for customer.
     *
     * @param int $customerId
     * @param int $productId
     * @return boolean
     */
    public function addProductToWishlistForCustomer($customerId, $productId): bool;
}