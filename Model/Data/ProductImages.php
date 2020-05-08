<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface;

use \Magento\Framework\Data\Collection;

class ProductImages implements ProductImagesInterface
{
    /** @var int|null $id */
    private $id;

    /** @var string|null $width */
    private $width;

    /** @var string|null $height */
    private $height;

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
        $this->id        = !empty($image['id']) ? $image['id'] : null;
        $this->mediaType = !empty($image['media_type']) ? $image['media_type'] : null;
        $this->label     = !empty($image['label']) ? $image['label'] : null;
        $this->position  = !empty($image['position']) ? $image['position'] : null;
        $this->types     = !empty($image['types']) ? $image['types'] : null;
        $this->file      = !empty($image['file']) ? $image['file'] : null;
        $this->url       = !empty($image['url']) ? $image['url'] : null;
        $this->url       = !empty($image['url']) ? $image['url'] : null;
        $this->width     = !empty($image['width']) ? $image['width'] : 'auto';
        $this->height    = !empty($image['height']) ? $image['height'] : 'auto';

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @inheritDoc
     */
    public function getHeight()
    {
        return $this->height;
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


    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        $this->$id = $id;
    }

    /**
     * @inheritDoc
     */
    public function setWidth($width)
    {
        $this->$width = $width;
    }

    /**
     * @inheritDoc
     */
    public function setHeight($height)
    {
        $this->$height = $height;
    }

    /**
     * @inheritDoc
     */
    public function setMediaType($media_type)
    {
        $this->$media_type = $media_type;
    }

    /**
     * @inheritDoc
     */
    public function setLabel($label)
    {
        $this->$label = $label;
    }

    /**
     * @inheritDoc
     */
    public function setPosition($position)
    {
        $this->$position = $position;
    }

    /**
     * @inheritDoc
     */
    public function setTypes($types)
    {
        $this->$types = $types;
    }

    /**
     * @inheritDoc
     */
    public function setFile($file)
    {
        $this->$file = $file;
    }

    /**
     * @inheritDoc
     */
    public function setUrl($url)
    {
        $this->$url = $url;
    }
}