<?php

namespace Appstractsoftware\MagentoAdapter\Api;

interface WishlistRepositoryInterface
{
    /**
     * Get Wishlist by sharing code
     * 
     * @param string $sharingCode
     * @return array
     */
    public function get($sharingCode);

    /**
     * Get Wishlist by id
     * 
     * @param int $id
     * @return array
     */
    public function getById($id);

    /**
     * Delete wishlist by id
     *
     * @param int $id
     * @return bool
     */
    public function deleteById($id);
}