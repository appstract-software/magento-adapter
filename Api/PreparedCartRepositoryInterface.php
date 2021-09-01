<?php
namespace Appstractsoftware\MagentoAdapter\Api;

interface PreparedCartRepositoryInterface
{
    /**
     * Create prepared guest cart based on existing admin-customer cart.
     *
     * @param int $cartId The admin-customer cart ID.
     * @return string Guest cart ID.
     */
    public function createGuestCartBasedOnAdminCustomerCart($cartId);

    /**
     * Set admin-customer cart as inactive and assign new one.
     *
     * @param int $customerId The admin-customer ID.
     * @param int $cartId The admin-customer cart ID.
     * @return int New cart ID.
     */
    public function emptyPreparedAdminCustomerCart($customerId, $cartId);

    /**
     * Apply prepared guest cart to customer cart.
     *
     * @param string $preparedCartId The prepared guest cart ID.
     * @param int $customerId The customer ID.
     * @return string New cart ID.
     */
    public function applyPreparedQuestCartToCustomerCart($preparedCartId, $customerId);
}
