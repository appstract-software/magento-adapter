<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface;

use \Magento\Framework\Data\Collection;

class ProductImages implements ProductImagesInterface
{
    /** @var int $id */
    private $id;

    /** @var string $media_type */
    private $mediaType;

    /** @var string $label */
    private $label;

    /** @var string $position */
    private $position;

    /** @var string[]|null $types */
    private $types;

    /** @var string $file */
    private $file;

    /** @var string $url */
    private $url;

    /**
     * @inheritDoc
     */
    public function load($image)
    {
        $this->id = $image['id'];
        $this->mediaType = $image['media_type'];
        $this->label = $image['label'];
        $this->position = $image['position'];
        $this->types = $image['types'];
        $this->file = $image['file'];
        $this->url = $image['url'];

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
    public function getMediaType(): string
    {
        return $this->mediaType;
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
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * @inheritDoc
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @inheritDoc
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        return $this->url;
    }


}