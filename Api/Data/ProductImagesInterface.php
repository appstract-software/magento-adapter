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
     * @return string
     */
    public function getMediaType(): string;

    /**
     * Get Label
     *
     * @return string|null
     */
    public function getLabel();

    /**
     * Get Position
     *
     * @return string
     */
    public function getPosition(): string;

    /**
     * Get Types
     *
     * @return string[]|null
     */
    public function getTypes();

    /**
     * Get File
     *
     * @return string
     */
    public function getFile(): string;

    /**
     * Get Url
     *
     * @return string
     */
    public function getUrl(): string;
}
