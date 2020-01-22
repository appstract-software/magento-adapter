<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\CategoryFiltersItemDtoInterface;

class CategoryFiltersItemDto implements CategoryFiltersItemDtoInterface
{
    /** @var string $label  */
    private $label;

    /** @var string $value  */
    private $value;

    /** @var string $name  */
    private $name;

    /** @var int $count  */
    private $count;

    /**
     * @inheritDoc
     */
    public function load($item)
    {
        $this->label = strip_tags($item->getLabel());
        $this->value = $item->getValueString();
        $this->name  = $item->getName();
        $this->count = $item->getCount();
        return $this;
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
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @inheritDoc
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function getCount(): int
    {
        return $this->count;
    }
}
