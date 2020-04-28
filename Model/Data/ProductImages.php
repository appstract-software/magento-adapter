<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface;

use \Magento\Framework\Data\Collection;

class ProductImages implements ProductImagesInterface
{
    /** @var int $id */
    private $id;

    /** @var string|null $media_type */
    private $mediaType;

    /** @var string|null $label */
    private $label;

    /** @var string|null $position */
    private $position;

    /** @var string[]|null $types */
    private $types;

    /** @var string|null $file */
    private $file;

    /** @var string|null $url */
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
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * @inheritDoc
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @inheritDoc
     */
    public function getPosition()
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
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @inheritDoc
     */
    public function getUrl()
    {
        return $this->url;
    }


}