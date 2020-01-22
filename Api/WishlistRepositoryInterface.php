<?php

namespace Appstractsoftware\MagentoAdapter\Api;

use Magento\Wishlist\Model\Wishlist;

interface WishlistRepositoryInterface
{
    /**
     * Get Wishlist by sharing code
     * 
     * @param string $sharingCode
     * @return Magento\Wishlist\Model\Wishlist
     */
    public function get($sharingCode);

    /**
     * Get Wishlist by id
     * 
     * @param int $id
     * @return Magento\Wishlist\Model\Wishlist
     */
    public function getById($id);

    /**
     * Get Wishlist by customer id
     * 
     * @param int $customerId
     * @return Magento\Wishlist\Model\Wishlist
     */
    public function getByCustomerId($customerId);

    /**
     * Delete wishlist by id
     *
     * @param int $id
     * @return bool
     */
    public function deleteById($id) : bool;
}