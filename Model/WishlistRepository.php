<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use Appstractsoftware\MagentoAdapter\Api\WishlistRepositoryInterface;

use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Wishlist\Model\ResourceModel\Wishlist as WishlistResource;
use Magento\Wishlist\Model\Wishlist;
use Magento\Wishlist\Model\WishlistFactory;

/**
 * WishlistRepository.
 * 
 * @author Mateusz Lesiak <mateusz.lesiak@appstract.software>
 * @copyright 2020 Appstract Software
 */
class WishlistRepository implements WishlistRepositoryInterface
{
    /** @var WishlistResource */
    private $wishlistResource;

    /** @var WishlistFactory */
    private $wishlistFactory;

    /**
     * Sharing code column name in wishlist table in db.
     */
    const SHARING_CODE_FIELD = 'sharing_code';

    /**
     * Customer id column name in wishlist table in db.
     */
    const CUSTOMER_ID_FIELD = 'customer_id';

    /**
     * WishlistRepository constructor.
     * 
     * @param WishlistFactory $wishlistFactory
     * @param WishlistResource $wishlistResource
     */
    public function __construct(
        WishlistFactory $wishlistFactory,
        WishlistResource $wishlistResource
    ) {
        $this->wishlistResource = $wishlistResource;
        $this->wishlistFactory = $wishlistFactory;
    }

    /**
     * @inheritdoc
     */
    public function get($sharingCode): Wishlist
    {
        $wishlist = $this->wishlistFactory->create();
        $this->wishlistResource->load($wishlist, $sharingCode, self::SHARING_CODE_FIELD);
        if (!$wishlist->getId()) {
            throw new NoSuchEntityException(__('Wishlist with sharing code "%1" does not exist.', $sharingCode));
        }
        return $wishlist;
    }
 
    /**
     * @inheritdoc
     */
    public function getById($id): Wishlist
    {
        $wishlist = $this->wishlistFactory->create();
        $this->wishlistResource->load($wishlist, $id);
        if (!$wishlist->getId()) {
            throw new NoSuchEntityException(__('Wishlist with id "%1" does not exist.', $id));
        }
        return $wishlist;
    }

    /**
     * @inheritdoc
     */
    public function getByCustomerId($customerId): Wishlist
    {
        if (empty($customerId)) {
            throw new InputException(__('Argument "customerId" is required'));
        }

        $wishlist = $this->wishlistFactory->create()->loadByCustomerId($customerId, true);
        if (!$wishlist->getId()) {
            throw new NoSuchEntityException(__('Wishlist with customer id "%1" does not exist.', $customerId));
        }
        return $wishlist;
    }


    /**
     * @inheritdoc
     */
    public function deleteById($id): bool
    {
        $wishlist = $this->wishlistFactory->create();
        $this->wishlistResource->load($wishlist, $id);
        try {
            $this->wishlistResource->delete($wishlist);
        } catch (\Exception $e) {
            throw new StateException(__('Cannot delete wishlist.'));
        }
        return true;
    }
}

