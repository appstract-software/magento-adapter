<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\WishlistDtoInterface;
use Appstractsoftware\MagentoAdapter\Api\Data\ItemDtoInterface;

use Magento\Wishlist\Model\Wishlist;

class WishlistDto implements WishlistDtoInterface
{
    /** @var int id */
    private $id;

    /** @var int customer_id */
    private $customer_id;

    /** @var string sharing_code */
    private $sharing_code;

    /** @var bool shared */
    private $shared;

    /** @var string name */
    private $name;

    /** @var string updated_at */
    private $updated_at;

    /** @var bool has_salable_items */
    private $has_salable_items;

    /** @var int items_count */
    private $items_count;

    /** @var Appstractsoftware\MagentoAdapter\Api\Data\ItemDtoInterface[] items */
    private $items;

    /** @var Magento\Wishlist\Model\ResourceModel\Item\Collection collection */
    private $collection;


    /** @var Appstractsoftware\MagentoAdapter\Api\Data\ItemDtoInterface item */
    private $itemLoader;

    /**
     * Constructor.
     *
     * @param ItemDtoInterface $itemLoader
     */
    public function __construct(ItemDtoInterface $itemLoader) {
        $this->itemLoader = $itemLoader;
    }

    /**
     * @inheritDoc
     */
    public function load($wishlist)
    {
        $this->id                = $wishlist->getId();
        $this->customer_id       = $wishlist->getCustomerId();
        $this->sharing_code      = $wishlist->getSharingCode();
        $this->shared            = $wishlist->getShared();
        $this->name              = $wishlist->getName();
        $this->updated_at        = $wishlist->getUpdatedAt();
        $this->has_salable_items = $wishlist->isSalable();
        $this->items_count       = $wishlist->getItemsCount();
        $this->collection        = $wishlist->getItemCollection();
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
    public function getCustomerId(): int
    {
        return $this->customer_id;
    }

    /**
     * @inheritDoc
     */
    public function getSharingCode(): string
    {
        return $this->sharing_code;
    }

    /**
     * @inheritDoc
     */
    public function getShared(): bool
    {
        return $this->shared;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * @inheritDoc
     */
    public function getHasSalableItems(): bool
    {
        return $this->has_salable_items;
    }

    /**
     * @inheritDoc
     */
    public function getItemsCount(): int
    {
        return $this->items_count;
    }

    /**
     * @inheritDoc
     */
    public function getItems()
    {
        $items = [];
        foreach ($this->collection as $itembase) {
            $items[] = clone $this->itemLoader->load($itembase);
        }
        return $items;
    }
}