<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

interface ProductImagesInterface 
{
    /**
     * Load data for dto.
     *
     * @return Appstractsoftware\MagentoAdapter\Api\Data\ProductImagesInterface
     */
    public function load($image);

    /**
     * Get Id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get width
     *
     * @return string|null
     */
    public function getWidth();

    /**
     * Get height
     *
     * @return string|null
     */
    public function getHeight();

    /**
     * Get MediaType
     *
     * @return string|null
     */
    public function getMediaType();

    /**
     * Get Label
     *
     * @return string|null
     */
    public function getLabel();

    /**
     * Get Position
     *
     * @return string|null
     */
    public function getPosition();

    /**
     * Get Types
     *
     * @return string[]|null
     */
    public function getTypes();

    /**
     * Get File
     *
     * @return string|null
     */
    public function getFile();

    /**
     * Get Url
     *
     * @return string|null
     */
    public function getUrl();


    /**
     * Set Id
     *
     * @return void
     */
    public function setId($id);

    /**
     * Set width
     *
     * @return void
     */
    public function setWidth($width);

    /**
     * Set height
     *
     * @return void
     */
    public function setHeight($height);

    /**
     * Set MediaType
     *
     * @return void
     */
    public function setMediaType($media_type);

    /**
     * Set Label
     *
     * @return void
     */
    public function setLabel($label);

    /**
     * Set Position
     *
     * @return void
     */
    public function setPosition($position);

    /**
     * Set Types
     *
     * @return void
     */
    public function setTypes($types);

    /**
     * Set File
     *
     * @return void
     */
    public function setFile($file);

    /**
     * Set Url
     *
     * @return void
     */
    public function setUrl($url);
}
