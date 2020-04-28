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
     * @return int
     */
    public function getId(): int;

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
}
